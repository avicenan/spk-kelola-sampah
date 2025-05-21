<?php

namespace App\Http\Controllers;

use App\Models\Aktifitas;
use Illuminate\Http\Request;

class AktifitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:kepala_divisi');
    }

    public function index()
    {
        $allAktivitas = Aktifitas::orderBy('id', 'desc')->limit(25)->get(['id', 'created_at', 'deskripsi', 'jenis']);
        return view('aktivitas.index', compact('allAktivitas'));
    }

    public function show(Aktifitas $aktifitas)
    {
        return view('aktifitas.show', compact('aktifitas'));
    }

    public function destroy(Aktifitas $aktifitas)
    {
        //
    }
}
