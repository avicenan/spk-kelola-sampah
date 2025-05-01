<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $keputusans = Keputusan::orderBy('id', 'asc')->get(['id', 'judul', 'isi']);
        $jenisSampahs = JenisSampah::all(['id', 'nama']);
        return view('keputusan.index', compact('keputusans', 'jenisSampahs'));
    }

    public function create()
    {
        return view('keputusan.create');
    }

    // public function calculate(Request $request)
    // {

    //     // Semua Kriteria
    //     $kriterias = Kriteria::all(['id', 'nama', 'sifat', 'bobot']);

    //     // TPAs with jenis sampah
    //     $tpas = JenisSampah::find($request->jenis_sampah_id)->tpas;

    //     $nilaiAlternatifs = $tpas->mapWithKeys(function ($tpa) use ($request) {
    //         return [
    //             $tpa->id => [
    //                 'biaya' => $request->biaya,
    //                 'tingkat_kemacetan' => $request->tingkat_kemacetan,
    //                 'jarak' => $tpa->jarak
    //             ]
    //         ];
    //     });

    //     // Normalisasi Nilai
    //     foreach ($kriterias as $krit) {
    //         $nama = $krit['nama'];
    //         $sifat = $krit['sifat'];

    //         // Ambil semua nilai kriteria tersebut
    //         $values = collect($nilaiAlternatifs)->pluck($nama);
    //         $min = $values->min();
    //         $max = $values->max();

    //         foreach ($nilaiAlternatifs as $altId => $kriteriaNilai) {
    //             $val = $kriteriaNilai[$nama];
    //             $r = $sifat === 'benefit'
    //                 ? ($val / $max)
    //                 : ($min / $val);
    //             $normalisasi[$altId][$nama] = $r;
    //         }
    //     }

    //     return dd($normalisasi);

    //     // 4. Hitung skor akhir
    //     $hasil = [];

    //     foreach ($normalisasi as $altId => $row) {
    //         $skor = 0;
    //         foreach ($row as $nama => $nilaiNorm) {
    //             $bobot = $kriterias->firstWhere('nama', $nama)['bobot'];
    //             $skor += $nilaiNorm * $bobot;
    //         }
    //         $hasil[] = [
    //             'alternatif_id' => $altId,
    //             'skor' => round($skor, 4),
    //         ];
    //     }

    //     // 5. Urutkan hasil (nilai tertinggi terbaik)
    //     $hasil = collect($hasil)->sortByDesc('skor')->values();
    //     return dd($hasil);
    // }

    public function calculate(Request $request)
    {
        // 1. Ambil data jarak dari tabel TPA atau Alternatif
        $alternatifs = JenisSampah::find($request->jenis_sampah_id)->tpas; // pastikan model TPA sudah ada

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

                if (isset($nilaiTetap[$nama])) {
                    // dari form request, semua alternatif nilainya sama
                    $data[$nama] = $nilaiTetap[$nama];
                } elseif (isset($alt->$nama)) {
                    // dari field alternatif (e.g. jarak, aksesibilitas, dst)
                    $data[$nama] = $alt->$nama;
                } else {
                    $data[$nama] = 0; // fallback jika tidak ditemukan
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

        // 8.    View Keputusan
        $hasil = $hasil->map(function ($item, $index) use ($alternatifs, $nilaiTetap, $request) {
            $item['view'] = [
                'rank' => $index + 1,
                'nama' => $alternatifs->find($item['alternatif_id'])->nama,
                'alamat' => $alternatifs->find($item['alternatif_id'])->alamat,
                'jarak' => $alternatifs->find($item['alternatif_id'])->jarak,
                'kontak' => $alternatifs->find($item['alternatif_id'])->kontak,
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
        //
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
