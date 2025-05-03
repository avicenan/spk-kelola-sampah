<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\TPA;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kriterias = Kriteria::orderBy('id', 'asc')->get(['id', 'label', 'sifat', 'bobot', 'satuan_ukur', 'is_deletable']);
        $totalBobot = $kriterias->sum('bobot');
        return view('kriteria.index', compact('kriterias', 'totalBobot'));
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
            'satuan_ukur' => 'string|max:255',
        ]);
        try {
            $kriterium = Kriteria::create([
                'nama' => strtolower(str_replace(' ', '_', $request->label)),
                'label' => $request->label,
                'sifat' => $request->sifat,
                'bobot' => $request->bobot,
                'satuan_ukur' => $request->satuan_ukur,
                'is_deletable' => true
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Kriteria: ' . $e->getMessage());
        } finally {
            try {
                foreach (TPA::all() as $tpa) {
                    $tpa->kriterias()->attach($kriterium->id, ['nilai' => 0]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Kriteria: ' . $e->getMessage());
            }
            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
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
            'satuan_ukur' => 'string|max:255',
        ]);
        try {
            if ($kriterium->is_deletable == false) {
                $kriterium->update([
                    'bobot' => $request->bobot,
                    'satuan_ukur' => $request->satuan_ukur,
                ]);
            } else {
                $kriterium->update([
                    'nama' => strtolower(
                        str_replace(' ', '_', $request->label)
                    ),
                    'label' => $request->label,
                    'sifat' => $request->sifat,
                    'bobot' => $request->bobot,
                    'satuan_ukur' => $request->satuan_ukur,
                ]);
            }

            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Kriteria: ' . $e->getMessage());
        }
    }

    public function destroy(Kriteria $kriterium)
    {
        try {
            if ($kriterium->is_deletable == false) {
                return redirect()->back()->withInput()->with('error', 'Kriteria tidak dapat dihapus.');
            }
            $kriterium->delete();
            return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Kriteria: ' . $e->getMessage());
        }
    }
}
