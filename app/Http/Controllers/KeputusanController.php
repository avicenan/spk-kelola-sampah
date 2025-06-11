<?php

namespace App\Http\Controllers;

use App\Models\Aktifitas;
use App\Models\HasilKeputusan;
use App\Models\JenisSampah;
use App\Models\Keputusan;
use App\Models\Kriteria;
use App\Models\TPA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeputusanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keputusans = Keputusan::orderBy('id', 'desc')->get(['id', 'created_at', 'judul', 'keterangan']);
        $hasilKeputusans = HasilKeputusan::orderBy('id', 'asc')->limit(10)->get();
        $jenisSampahs = JenisSampah::all(['id', 'nama']);
        $tpas = TPA::with([
            'kriterias' => function ($query) {
                $query->select('kriterias.id', 'label', 'nama', 'satuan_ukur');
            }
        ])->orderBy('tpa.id', 'asc')->get(['tpa.id', 'tpa.nama']);
        $kriterias = Kriteria::get(['id', 'label', 'nama', 'satuan_ukur']);
        return view('keputusan.index', compact('keputusans', 'jenisSampahs', 'tpas', 'kriterias'));
    }

    public function create()
    {
        return view('keputusan.create');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'jumlah_sampah' => 'required|numeric|min:0',
            'from' => 'required|date|before:to',
            'to' => 'required|date|after:from',
            'tpa_kriteria' => 'required|array',
            'tpa_kriteria.*' => 'required|array',
            'tpa_kriteria.*.*' => 'required|numeric|min:0',
        ]);

        try {
            // 1. Ambil data TPA atau Alternatif
            $alternatifs = JenisSampah::find($request->jenis_sampah_id)->tpas;

            // 2. Definisikan kriteria dan bobot
            $kriterias = Kriteria::all(['id', 'nama', 'sifat', 'bobot', 'label', 'satuan_ukur']);

            // 3. Bangun nilai alternatif
            $nilaiAlternatif = [];

            foreach ($alternatifs as $alt) {
                $data = [];

                foreach ($kriterias as $k) {
                    $nama = $k['nama'];
                    $nilai = $request->tpa_kriteria[$alt->id][$k['id']] ?? 0;
                    $data[$nama] = $nilai;
                }

                $nilaiAlternatif[$alt->id] = $data;
            }

            // 4. Normalisasi
            $normalisasi = [];
            foreach ($kriterias as $krit) {
                $nama = $krit['nama'];
                $sifat = $krit['sifat'];

                $values = collect($nilaiAlternatif)->pluck($nama);
                $min = $values->min();
                $max = $values->max();

                foreach ($nilaiAlternatif as $altId => $data) {
                    $val = $data[$nama] ?? 0;
                    $r = $sifat === 'benefit'
                        ? ($max != 0 ? $val / $max : 0)
                        : ($val != 0 ? $min / $val : 0);
                    $normalisasi[$altId][$nama] = $r;
                }
            }

            // 5. Hitung skor akhir
            $hasil = [];
            foreach ($normalisasi as $altId => $row) {
                $skor = 0;
                foreach ($row as $nama => $nilaiNorm) {
                    $bobot = $kriterias->firstWhere('nama', $nama)['bobot'];
                    $skor += $nilaiNorm * $bobot;
                }

                $hasil[] = [
                    'alternatif_id' => $altId,
                    'skor' => round($skor * 100, 2),
                ];
            }

            // 6. Urutkan dari skor tertinggi
            $hasil = collect($hasil)->sortByDesc('skor')->values();

            // 7. View Keputusan
            $hasil = $hasil->map(function ($item, $index) use ($alternatifs, $request, $normalisasi, $nilaiAlternatif, $kriterias) {
                $alternatif = $alternatifs->find($item['alternatif_id']);
                $item['view'] = [
                    'rank' => $index + 1,
                    'nama' => $alternatif->nama,
                    'alamat' => $alternatif->alamat,
                    'kontak' => $alternatif->kontak,
                    'jenis_sampah' => JenisSampah::find($request->jenis_sampah_id)->nama,
                    'sumber_sampah' => JenisSampah::find($request->jenis_sampah_id)->sumber_sampah,
                    'from' => $request->from,
                    'to' => $request->to,
                    'jumlah_sampah' => $request->jumlah_sampah,
                    'nama_pengguna' => Auth::user()->name,
                    'email_pengguna' => Auth::user()->email,
                    'role' => Auth::user()->role,
                    'created_at' => now()
                ];
                $item['normalisasi'] = $normalisasi[$alternatif->id];
                $item['nilaiAlternatif'] = $nilaiAlternatif[$alternatif->id];
                $item['kriterias'] = $kriterias;
                return $item;
            });

            return response()->json($hasil);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghitung keputusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|json'
        ]);

        try {
            $hasils = collect(json_decode($request->data, true));
            $user = Auth::user();

            // Start database transaction
            return DB::transaction(function () use ($hasils, $user) {
                // Create keputusan
                $keputusan = Keputusan::create([
                    'user_id' => $user->id,
                    'judul' => $user->name . ' membuat keputusan baru',
                    'keterangan' => 'Keputusan baru dihasilkan oleh ' . $user->name . ' untuk ' . $hasils[0]['view']['jenis_sampah'] . ' seberat ' . $hasils[0]['view']['jumlah_sampah'] . ' kg. Hasil: ' . $hasils[0]['view']['nama'],
                ]);

                // Create hasil keputusan records
                $hasilKeputusanData = $hasils->map(function ($hasil) use ($keputusan) {
                    // Prepare criteria values
                    $kriteriaValues = [];
                    foreach ($hasil['kriterias'] as $kriteria) {
                        $kriteriaValues[$kriteria['nama']] = [
                            'label' => $kriteria['label'],
                            'satuan_ukur' => $kriteria['satuan_ukur'],
                            'nilai' => $hasil['nilaiAlternatif'][$kriteria['nama']]
                        ];
                    }

                    return [
                        'keputusan_id' => $keputusan->id,
                        'skor' => $hasil['skor'],
                        'rank' => $hasil['view']['rank'],
                        'nama' => $hasil['view']['nama'],
                        'alamat' => $hasil['view']['alamat'],
                        'kontak' => $hasil['view']['kontak'],
                        'jenis_sampah' => $hasil['view']['jenis_sampah'],
                        'sumber_sampah' => $hasil['view']['sumber_sampah'],
                        'from' => \Carbon\Carbon::parse($hasil['view']['from'])->format('Y-m-d H:i:s'),
                        'to' => \Carbon\Carbon::parse($hasil['view']['to'])->format('Y-m-d H:i:s'),
                        'jumlah_sampah' => $hasil['view']['jumlah_sampah'],
                        'nama_pengguna' => $hasil['view']['nama_pengguna'],
                        'email_pengguna' => $hasil['view']['email_pengguna'],
                        'role' => $hasil['view']['role'],
                        'kriterias' => json_encode($kriteriaValues),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                // Bulk insert hasil keputusan records
                HasilKeputusan::insert($hasilKeputusanData);

                // Create aktivitas record
                Aktifitas::create([
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'jenis' => 'add_keputusan',
                    'deskripsi' => '[' . $user->name . '] membuat keputusan baru untuk ' . $hasils[0]['view']['jenis_sampah'] . ' seberat ' . $hasils[0]['view']['jumlah_sampah'] . ' kg. Hasil: ' . $hasils[0]['view']['nama'],
                ]);

                return response()->json([
                    'message' => 'Keputusan berhasil disimpan',
                    'data' => $keputusan->hasils
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan keputusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getHasilKeputusan($aktivitasId)
    {
        $aktivitas = Aktifitas::find($aktivitasId);
        $hasilKeputusan = $aktivitas->keputusan->hasils;
        return response()->json($hasilKeputusan);
    }
}
