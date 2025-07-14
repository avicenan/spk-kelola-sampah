<?php

namespace App\Http\Controllers;

use App\Models\SampahHarian;
use App\Models\JenisSampah;
use App\Models\Aktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampahHarianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sampahHarian = SampahHarian::with(['jenisSampah', 'user'])
            ->orderBy('tanggal_input', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get chart data
        $chartData = $this->getChartData();

        return view('sampah-harian.index', compact('sampahHarian', 'chartData'));
    }

    public function store(Request $request)
    {
        // Check if this is a bulk operation
        if ($request->has('bulk_data') && is_array($request->bulk_data)) {
            return $this->storeBulk($request);
        }

        // Single item validation
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'volume_sampah' => 'required|numeric|min:0.01|max:1000',
            'sumber_sampah' => 'required|string|max:255',
            'tanggal_input' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (
                        !\DateTime::createFromFormat('Y-m-d H:i', $value) &&
                        !\DateTime::createFromFormat('Y-m-d H:i:s', $value)
                    ) {
                        $fail('The ' . $attribute . ' is not a valid datetime.');
                    }
                },
                'before_or_equal:' . now()->format('Y-m-d H:i:s'),
            ],
        ]);

        try {
            $sampahHarian = SampahHarian::create([
                'jenis_sampah_id' => $request->jenis_sampah_id,
                'volume_sampah' => $request->volume_sampah,
                'sumber_sampah' => $request->sumber_sampah,
                'tanggal_input' => \DateTime::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                    ? Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                    : Carbon::createFromFormat('Y-m-d H:i:s', $request->tanggal_input),
                'user_id' => Auth::user()->id
            ]);

            // Log activity
            Aktifitas::create([
                'user_id' => Auth::user()->id,
                'jenis' => 'add_sampah_harian',
                'deskripsi' => '[' . Auth::user()->name . '] menambahkan data sampah harian ' . $sampahHarian->jenisSampah->nama . ' sebesar ' . $sampahHarian->volume_sampah . ' kg',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sampah harian berhasil ditambahkan',
                'data' => $sampahHarian->load(['jenisSampah', 'user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data sampah harian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'bulk_data' => 'required|array|min:1',
            'bulk_data.*.jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'bulk_data.*.volume_sampah' => 'required|numeric|min:0.01|max:1000',
            'bulk_data.*.sumber_sampah' => 'required|string|max:255',
            'tanggal_input' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (
                        !\DateTime::createFromFormat('Y-m-d H:i', $value) &&
                        !\DateTime::createFromFormat('Y-m-d H:i:s', $value)
                    ) {
                        $fail('The ' . $attribute . ' is not a valid datetime.');
                    }
                },
                'before_or_equal:' . now()->format('Y-m-d H:i:s'),
            ],
        ]);

        try {
            DB::beginTransaction();

            $createdItems = [];
            $totalVolume = 0;
            $jenisSampahNames = [];

            foreach ($request->bulk_data as $item) {
                $sampahHarian = SampahHarian::create([
                    'jenis_sampah_id' => $item['jenis_sampah_id'],
                    'volume_sampah' => $item['volume_sampah'],
                    'sumber_sampah' => $item['sumber_sampah'],
                    'tanggal_input' => \DateTime::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                        ? Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                        : Carbon::createFromFormat('Y-m-d H:i:s', $request->tanggal_input),
                    'user_id' => Auth::user()->id
                ]);

                $createdItems[] = $sampahHarian->load(['jenisSampah', 'user']);
                $totalVolume += $item['volume_sampah'];
                $jenisSampahNames[] = $sampahHarian->jenisSampah->nama;
            }

            // Log activity for bulk operation
            Aktifitas::create([
                'user_id' => Auth::user()->id,
                'jenis' => 'add_sampah_harian_bulk',
                'deskripsi' => '[' . Auth::user()->name . '] menambahkan ' . count($createdItems) . ' data sampah harian: ' . implode(', ', $jenisSampahNames) . ' dengan total volume ' . number_format($totalVolume, 2) . ' kg',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan ' . count($createdItems) . ' data sampah harian dengan total volume ' . number_format($totalVolume, 2) . ' kg',
                'data' => $createdItems,
                'total_items' => count($createdItems),
                'total_volume' => $totalVolume
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data sampah harian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'volume_sampah' => 'required|numeric|min:0.01|max:1000',
            'sumber_sampah' => 'required|string|max:255',
            'tanggal_input' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (
                        !\DateTime::createFromFormat('Y-m-d H:i', $value) &&
                        !\DateTime::createFromFormat('Y-m-d H:i:s', $value)
                    ) {
                        $fail('The ' . $attribute . ' is not a valid datetime.');
                    }
                },
                'before_or_equal:' . now()->format('Y-m-d H:i:s'),
            ],
        ]);

        try {
            $sampahHarian = SampahHarian::findOrFail($id);

            $oldData = $sampahHarian->toArray();

            $sampahHarian->update([
                'jenis_sampah_id' => $request->jenis_sampah_id,
                'volume_sampah' => $request->volume_sampah,
                'sumber_sampah' => $request->sumber_sampah,
                'tanggal_input' => \DateTime::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                    ? Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_input)
                    : Carbon::createFromFormat('Y-m-d H:i:s', $request->tanggal_input)
            ]);

            // Log activity
            Aktifitas::create([
                'user_id' => Auth::user()->id,
                'jenis' => 'edit_sampah_harian',
                'deskripsi' => '[' . Auth::user()->name . '] mengubah data sampah harian dari ' . $oldData['volume_sampah'] . ' kg menjadi ' . $request->volume_sampah . ' kg',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sampah harian berhasil diperbarui',
                'data' => $sampahHarian->load(['jenisSampah', 'user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data sampah harian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sampahHarian = SampahHarian::findOrFail($id);
            $volume = $sampahHarian->volume_sampah;
            $jenisSampah = $sampahHarian->jenisSampah->nama;

            $sampahHarian->delete();

            // Log activity
            Aktifitas::create([
                'user_id' => Auth::user()->id,
                'jenis' => 'delete_sampah_harian',
                'deskripsi' => '[' . Auth::user()->name . '] menghapus data sampah harian ' . $jenisSampah . ' sebesar ' . $volume . ' kg',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sampah harian berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data sampah harian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getChartData()
    {
        // Get data for the last 30 days
        $thirtyDaysAgo = now()->subDays(30)->startOfDay();

        $chartData = SampahHarian::with('jenisSampah')
            ->where('tanggal_input', '>=', $thirtyDaysAgo)
            ->select('jenis_sampah_id', DB::raw('SUM(volume_sampah) as total_volume'))
            ->groupBy('jenis_sampah_id')
            ->orderByDesc('total_volume')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->jenisSampah->nama,
                    'total_volume' => (float) $item->total_volume
                ];
            });

        return $chartData;
    }

    public function getJenisSampah()
    {
        $jenisSampah = JenisSampah::where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($jenisSampah);
    }
}
