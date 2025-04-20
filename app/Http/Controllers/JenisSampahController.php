<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JenisSampahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jenisSampah = JenisSampah::orderBy('nama')->get();
        return view('jenis-sampah.index', compact('jenisSampah'));
    }

    public function create()
    {
        return view('jenis-sampah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_sampah,nama',
            'sumber_sampah' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            JenisSampah::create([
                'nama' => $request->nama,
                'sumber_sampah' => $request->sumber_sampah,
                'is_active' => $request->is_active ?? true
            ]);

            Session::flash('success', 'Jenis sampah berhasil ditambahkan');
            return redirect()->route('jenis-sampah.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Gagal menambahkan jenis sampah: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function show(JenisSampah $jenisSampah)
    {
        // return view('jenis-sampah.show', compact('jenisSampah'));
    }

    public function edit(JenisSampah $jenisSampah)
    {
        // return view('jenis-sampah.edit', compact('jenisSampah'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        // $request->validate([
        //     'nama' => 'required|string|max:255|unique:jenis_sampah,nama,' . $jenisSampah->id,
        //     'sumber_sampah' => 'nullable|string',
        //     'is_active' => 'boolean'
        // ]);

        // try {
        //     $jenisSampah->update([
        //         'nama' => $request->nama,
        //         'sumber_sampah' => $request->sumber_sampah,
        //         'is_active' => $request->is_active ?? true
        //     ]);

        //     Session::flash('success', 'Jenis sampah berhasil diperbarui');
        //     return redirect()->route('jenis-sampah.index');
        // } catch (\Exception $e) {
        //     Session::flash('error', 'Gagal memperbarui jenis sampah: ' . $e->getMessage());
        //     return redirect()->back()->withInput();
        // }
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        // try {
        //     $jenisSampah->delete();
        //     Session::flash('success', 'Jenis sampah berhasil dihapus');
        // } catch (\Exception $e) {
        //     Session::flash('error', 'Gagal menghapus jenis sampah: ' . $e->getMessage());
        // }

        // return redirect()->route('jenis-sampah.index');
    }
}
