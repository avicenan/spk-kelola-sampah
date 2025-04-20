<?php

namespace App\Http\Controllers;

use App\Models\TPA;
use Illuminate\Http\Request;

class TPAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tpa.index');
    }

    public function create()
    {
        return view('tpa.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(TPA $tpa)
    {
        return view('tpa.show', compact('tpa'));
    }

    public function edit(TPA $tpa)
    {
        return view('tpa.edit', compact('tpa'));
    }

    public function update(Request $request, TPA $tpa)
    {
        //
    }

    public function destroy(TPA $tpa)
    {
        //
    }
}
