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
        $jenisSampah = JenisSampah::orderBy('id', 'asc')->get(['id', 'nama', 'sumber_sampah']);
        return view('jenis-sampah.index', compact('jenisSampah'));
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

            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jenis sampah: ' . $e->getMessage());
        }
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_sampah,nama,' . $jenisSampah->id,
            'sumber_sampah' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $jenisSampah->update([
                'nama' => $request->nama,
                'sumber_sampah' => $request->sumber_sampah,
                'is_active' => $request->is_active ?? true
            ]);

            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jenis sampah: ' . $e->getMessage());
        }
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        try {
            $jenisSampah->delete();
            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus jenis sampah: ' . $e->getMessage());
        }
    }
}
