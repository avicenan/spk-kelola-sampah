<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\TPAController;
use App\Http\Controllers\KeputusanController;
use App\Http\Controllers\AktifitasController;
use App\Http\Controllers\KriteriaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Jenis Sampah
    Route::resource('jenis-sampah', JenisSampahController::class);

    // TPA
    Route::resource('tpa', TPAController::class);

    // Keputusan
    Route::resource('keputusan', KeputusanController::class);

    // Aktifitas
    Route::resource('aktifitas', AktifitasController::class);

    // Kriteria
    Route::resource('kriteria', KriteriaController::class);
});
