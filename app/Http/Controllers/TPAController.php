<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\TPA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TPAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $tpas = TPA::with('jenisSampah:id,nama')->orderBy('id', 'asc')->get(['id', 'nama', 'alamat', 'jarak', 'kontak']);
        $allJenisSampah = JenisSampah::all(['id', 'nama']);
        return view('tpa.index', compact('tpas', 'allJenisSampah'));
    }

    public function create()
    {
        return view('tpa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jarak' => 'required|numeric|min:0',
            'kontak' => 'nullable|string|max:255',
            'jenis_sampah' => 'array',
            'jenis_sampah.*' => 'integer|exists:jenis_sampah,id',
            'is_active' => 'boolean'
        ]);

        try {
            $tpa = TPA::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jarak' => $request->jarak,
                'kontak' => $request->kontak,
                'is_active' => $request->is_active ?? true
            ]);

            // Handle jenis_sampah[] attach
            if ($request->has('jenis_sampah') && is_array($request->jenis_sampah)) {
                $tpa->jenisSampah()->attach($request->jenis_sampah);
            }

            return redirect()->route('tpa.index')->with('success', 'TPA berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan TPA: ' . $e->getMessage());
        }
    }

    public function show(TPA $tpa)
    {
        return response()->json($tpa);
    }

    public function edit(TPA $tpa)
    {
        // return view('tpa.edit', compact('tpa'));
    }

    public function update(Request $request, TPA $tpa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jarak' => 'required|numeric|min:0',
            'kontak' => 'nullable|string|max:255',
            'jenis_sampah' => 'array',
            'jenis_sampah.*' => 'integer|exists:jenis_sampah,id',
            'is_active' => 'boolean',
        ]);

        try {
            $tpa->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jarak' => $request->jarak,
                'kontak' => $request->kontak,
                'is_active' => $request->is_active ?? true
            ]);

            // Handle jenis_sampah[] sync
            if ($request->has('jenis_sampah')) {
                $tpa->jenisSampah()->sync($request->jenis_sampah);
            } else {
                $tpa->jenisSampah()->sync([]);
            }

            return redirect()->route('tpa.index')->with('success', 'TPA berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui TPA: ' . $e->getMessage());
        }
    }

    public function destroy(TPA $tpa)
    {
        try {
            $tpa->delete();
            return redirect()->route('tpa.index')->with('success', 'TPA berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus TPA: ' . $e->getMessage());
        }
    }
}
