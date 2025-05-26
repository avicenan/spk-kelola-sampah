<?php

namespace App\Http\Controllers;

use App\Models\Aktifitas;
use App\Models\JenisSampah;
use App\Models\Kriteria;
use App\Models\TPA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TPAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tpas = TPA::with([
            'jenisSampah:id,nama',
            'kriterias' => function ($query) {
                $query->select('kriterias.id', 'label', 'nama', 'satuan_ukur')
                    ->whereNotIn('nama', ['biaya', 'tingkat_kemacetan']);
            }
        ])->orderBy('tpa.id', 'asc')->get(['tpa.id', 'tpa.nama', 'alamat', 'kontak']);
        $allJenisSampah = JenisSampah::all(['id', 'nama']);
        $kriterias = Kriteria::whereNotIn('nama', ['biaya', 'tingkat_kemacetan'])->get(['id', 'label', 'nama', 'satuan_ukur']);
        return view('tpa.index', compact('tpas', 'allJenisSampah', 'kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'nullable|string|max:255',
            'jenis_sampah' => 'array',
            'jenis_sampah.*' => 'integer|exists:jenis_sampah,id',
            'is_active' => 'boolean'
        ]);

        try {
            $tpa = TPA::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak,
                'is_active' => $request->is_active ?? true
            ]);

            // Handle jenis_sampah[] attach
            if ($request->has('jenis_sampah') && is_array($request->jenis_sampah)) {
                $tpa->jenisSampah()->attach($request->jenis_sampah);
            }

            // Handle kriterias[] attach
            if ($request->has('kriterias')) {
                $kriterias = [];
                foreach ($request->kriterias as $kriteriaId => $nilai) {
                    $kriterias[$kriteriaId] = ['nilai' => $nilai];
                }
                $tpa->kriterias()->attach($kriterias);
            } else {
                $tpa->kriterias()->attach([]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan TPA: ' . $e->getMessage());
        } finally {
            try {
                Aktifitas::create([
                    'user_id' => Auth::user()->id,
                    'jenis' => 'add_tpa',
                    'deskripsi' => '[' . Auth::user()->name . '] menambahkan TPA ' . $request->nama
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan TPA: ' . $e->getMessage());
            }
            return redirect()->route('tpa.index')->with('success', 'TPA berhasil ditambahkan');
        }
    }

    public function update(Request $request, TPA $tpa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'nullable|string|max:255',
            'jenis_sampah' => 'array',
            'jenis_sampah.*' => 'integer|exists:jenis_sampah,id',
            'is_active' => 'boolean',
            'kriterias' => 'array'
        ]);

        try {
            $tpa->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak,
                'is_active' => $request->is_active ?? true
            ]);

            // Handle jenis_sampah[] sync
            if ($request->has('jenis_sampah')) {
                $tpa->jenisSampah()->sync($request->jenis_sampah);
            } else {
                $tpa->jenisSampah()->sync([]);
            }

            // Handle kriterias[] sync
            if ($request->has('kriterias')) {
                $kriterias = [];
                foreach ($request->kriterias as $kriteriaId => $nilai) {
                    $kriterias[$kriteriaId] = ['nilai' => $nilai];
                }
                $tpa->kriterias()->sync($kriterias);
            } else {
                $tpa->kriterias()->sync([]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui TPA: ' . $e->getMessage());
        } finally {
            try {
                Aktifitas::create([
                    'user_id' => Auth::user()->id,
                    'jenis' => 'edit_tpa',
                    'deskripsi' => '[' . Auth::user()->name . '] memperbarui TPA ' . $request->nama
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui TPA: ' . $e->getMessage());
            }
            return redirect()->route('tpa.index')->with('success', 'TPA berhasil diperbarui');
        }
    }

    public function destroy(TPA $tpa)
    {
        try {
            $tpa->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus TPA: ' . $e->getMessage());
        } finally {
            try {
                Aktifitas::create([
                    'user_id' => Auth::user()->id,
                    'jenis' => 'delete_tpa',
                    'deskripsi' => '[' . Auth::user()->name . '] menghapus TPA ' . $tpa->nama
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus TPA: ' . $e->getMessage());
            }
            return redirect()->route('tpa.index')->with('success', 'TPA berhasil dihapus');
        }
    }
}
