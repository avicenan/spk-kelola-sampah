<?php

namespace App\Http\Controllers;

use App\Models\Keputusan;
use Illuminate\Http\Request;

class KeputusanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('keputusan.index');
    }

    public function create()
    {
        return view('keputusan.create');
    }

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
