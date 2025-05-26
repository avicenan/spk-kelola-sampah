<?php

namespace App\Http\Controllers;

use App\Models\HasilKeputusan;
use Illuminate\Http\Request;

class HasilKeputusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $hasilKeputusan = HasilKeputusan::
        return response()->json('oke');
    }

    /**
     * Display the specified resource.
     */
    public function show(HasilKeputusan $hasilKeputusan)
    {
        //
    }
}
