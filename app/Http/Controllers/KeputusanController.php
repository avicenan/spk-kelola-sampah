<?php

namespace App\Http\Controllers;

use App\Models\HasilKeputusan;
use App\Models\JenisSampah;
use App\Models\Keputusan;
use App\Models\Kriteria;
use App\Models\TPA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('keputusan.index', compact('keputusans', 'jenisSampahs'));
    }

    public function create()
    {
        return view('keputusan.create');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'biaya' => 'required|numeric|min:0',
            'tingkat_kemacetan' => 'required|in:1,2,3,4,5',
            'from' => 'required|date',
            'to' => 'required|date',
            'jumlah_sampah' => 'required|numeric|min:0',
        ]);

        try {
            // 1. Ambil data TPA atau Alternatif
            $alternatifs = JenisSampah::find($request->jenis_sampah_id)->tpas;

            // 2. Ambil nilai tetap dari request
            $nilaiTetap = [
                'biaya' => $request->biaya,
                'tingkat_kemacetan' => $request->tingkat_kemacetan
            ];

            // 3. Definisikan kriteria dan bobot
            $kriterias = Kriteria::all(['id', 'nama', 'sifat', 'bobot']);

            // 4. Bangun nilai alternatif
            $nilaiAlternatif = [];

            foreach ($alternatifs as $alt) {
                $data = [];

                foreach ($kriterias as $k) {
                    $nama = $k->nama;

                    if (array_key_exists($nama, $nilaiTetap)) {
                        // dari form request, semua alternatif nilainya sama
                        $data[$nama] = $nilaiTetap[$nama];
                    } else {
                        // dari tpa_kriteria pivot table
                        $data[$nama] = $alt->kriterias()->firstWhere('nama', $nama)->pivot->nilai ?? 0;
                    }
                }

                $nilaiAlternatif[$alt->id] = $data;
            }

            // 5. Normalisasi
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

            // 6. Hitung skor akhir
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

            // 7. Urutkan dari skor tertinggi
            $hasil = collect($hasil)->sortByDesc('skor')->values();

            // 8. View Keputusan
            $hasil = $hasil->map(function ($item, $index) use ($alternatifs, $nilaiTetap, $request) {
                $alternatif = $alternatifs->find($item['alternatif_id']);
                $item['view'] = [
                    'rank' => $index + 1,
                    'nama' => $alternatif->nama,
                    'alamat' => $alternatif->alamat,
                    'kontak' => $alternatif->kontak,
                    'jarak' => $alternatif->kriterias()->firstWhere('nama', 'jarak')->pivot->nilai,
                    'biaya' => $nilaiTetap['biaya'],
                    'tingkat_kemacetan' => $nilaiTetap['tingkat_kemacetan'],
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

    // public function getView(Request $request)
    // {
    //     $tpa = TPA::find($request->tpa_id);
    //     $jenisSampah = JenisSampah::find($tpa->jenis_sampah_id);


    //     $view = [
    //         'tanggal' => now(),
    //         'rank' => $request->hasil['rank'],
    //         'nama' => $tpa->nama,
    //         'alamat' => $tpa->alamat,
    //         'jarak' => $tpa->jarak,
    //         'kontak' => $tpa->kontak,
    //         'biaya' => $request->biaya,
    //         'jenis_sampah' => $jenisSampah->nama,
    //         'tingkat_kemacetan' => $request->tingkat_kemacetan

    //     ];
    // }

    public function store(Request $request)
    {
        try {
            $hasils = collect(json_decode($request->data, true));

            $user = Auth::user();

            $keputusan = Keputusan::create([
                'user_id' => $user->id,
                'judul' => $user->name . ' membuat keputusan baru',
                'keterangan' => 'Keputusan baru dihasilkan oleh ' . $user->name . ' untuk ' . $hasils[0]['view']['jenis_sampah'] . ' seberat ' . $hasils[0]['view']['jumlah_sampah'] . ' kg dengan biaya sebesar Rp ' . $hasils[0]['view']['biaya'] . ' Hasil: ' . $hasils[0]['view']['nama'],
            ]);

            return response()->json($hasils);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan keputusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Keputusan $keputusan)
    {
        return view('keputusan.show', compact('keputusan'));
    }

    public function edit(Keputusan $keputusan)
    {
        return view('keputusan.edit', compact('keputusan'));
    }

    public function update(Request $request, Keputusan $keputusan)
    {
        //
    }

    public function destroy(Keputusan $keputusan)
    {
        //
    }
}
