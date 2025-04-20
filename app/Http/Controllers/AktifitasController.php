<?php

namespace App\Http\Controllers;

use App\Models\Aktifitas;
use Illuminate\Http\Request;

class AktifitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('aktifitas.index');
    }

    public function create()
    {
        return view('aktifitas.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Aktifitas $aktifitas)
    {
        return view('aktifitas.show', compact('aktifitas'));
    }

    public function edit(Aktifitas $aktifitas)
    {
        return view('aktifitas.edit', compact('aktifitas'));
    }

    public function update(Request $request, Aktifitas $aktifitas)
    {
        //
    }

    public function destroy(Aktifitas $aktifitas)
    {
        //
    }
}
