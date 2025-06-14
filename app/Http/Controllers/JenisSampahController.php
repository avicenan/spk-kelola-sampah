<?php

namespace App\Http\Controllers;

use App\Models\Aktifitas;
use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JenisSampahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jenisSampah = JenisSampah::orderBy('id', 'asc')->get(['id', 'nama', 'sumber_sampah', 'contoh_sampah']);
        return view('jenis-sampah.index', compact('jenisSampah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_sampah,nama',
            'sumber_sampah' => 'nullable|string',
            'contoh_sampah' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $jenisSampah = JenisSampah::create([
                'nama' => $request->nama,
                'sumber_sampah' => $request->sumber_sampah,
                'contoh_sampah' => $request->contoh_sampah,
                'is_active' => $request->is_active ?? true
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jenis sampah: ' . $e->getMessage());
        } finally {
            try {
                Aktifitas::create([
                    'user_id' => Auth::user()->id,
                    'jenis' => 'add_jenis_sampah',
                    'deskripsi' => '[' . Auth::user()->name . '] menambahkan jenis sampah ' . $jenisSampah->nama,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jenis sampah: ' . $e->getMessage());
            }

            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan');
        }
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_sampah,nama,' . $jenisSampah->id,
            'sumber_sampah' => 'nullable|string',
            'contoh_sampah' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $jenisSampah->update([
                'nama' => $request->nama,
                'sumber_sampah' => $request->sumber_sampah,
                'contoh_sampah' => $request->contoh_sampah,
                'is_active' => $request->is_active ?? true
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jenis sampah: ' . $e->getMessage());
        } finally {
            try {
                Aktifitas::create([
                    'user_id' => Auth::user()->id,
                    'jenis' => 'edit_jenis_sampah',
                    'deskripsi' => '[' . Auth::user()->name . '] memperbarui jenis sampah ' . $jenisSampah->nama,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jenis sampah: ' . $e->getMessage());
            }
            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui');
        }
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        try {
            $jenisSampah->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus jenis sampah: ' . $e->getMessage());
        } finally {
            Aktifitas::create([
                'user_id' => Auth::user()->id,
                'jenis' => 'delete_jenis_sampah',
                'deskripsi' => '[' . Auth::user()->name . '] menghapus jenis sampah ' . $jenisSampah->nama
            ]);
            return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus');
        }
    }
}
