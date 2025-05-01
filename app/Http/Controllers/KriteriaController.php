<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kriterias = Kriteria::orderBy('id', 'asc')->get(['id', 'label', 'sifat', 'bobot']);
        return view('kriteria.index', compact('kriterias'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'sifat' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);
        try {
            Kriteria::create([
                'nama' => strtolower(str_replace(' ', '_', $request->label)),
                'label' => $request->label,
                'sifat' => $request->sifat,
                'bobot' => $request->bobot,
            ]);
            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Kriteria: ' . $e->getMessage());
        }
    }

    public function show(Kriteria $kriterium)
    {
        //
    }

    public function edit(Kriteria $kriterium)
    {
        //
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'sifat' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);
        try {
            $kriterium->update([
                'nama' => strtolower(
                    str_replace(' ', '_', $request->label)
                ),
                'label' => $request->label,
                'sifat' => $request->sifat,
                'bobot' => $request->bobot,
            ]);
            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Kriteria: ' . $e->getMessage());
        }
    }

    public function destroy(Kriteria $kriterium)
    {
        try {
            $kriterium->delete();
            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Kriteria: ' . $e->getMessage());
        }
    }
}
