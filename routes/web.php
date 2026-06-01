<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function (Request $request) {
    if ($request->user()->hasRole('manajer')) {
        return redirect()->route('manajer.dashboard');
    } elseif ($request->user()->hasRole('direktur')) {
        return redirect()->route('direktur.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Manajer Routes
Route::middleware(['auth', 'verified', 'role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['role' => 'Manajer']);
    })->name('dashboard');

    // Users CRUD
    Route::resource('users', \App\Http\Controllers\Manajer\UserController::class);
    // Kriteria CRUD
    Route::resource('kriteria', \App\Http\Controllers\Manajer\KriteriaController::class)->parameters([
        'kriteria' => 'kriteria' // prevent laravel from guessing 'kriterium'
    ]);
    // Lokasi CRUD
    Route::resource('lokasi', \App\Http\Controllers\Manajer\LokasiController::class);
    // Observasi CRUD
    Route::resource('observasi', \App\Http\Controllers\Manajer\ObservasiController::class)->except(['edit', 'update']);
    Route::get('/penilaian', function() { return 'Manage Penilaian'; })->name('penilaian.index')->middleware('permission:manage penilaian');
    Route::get('/perhitungan', function() { return 'Process Perhitungan'; })->name('perhitungan.index')->middleware('permission:process perhitungan');
    Route::get('/hasil', function() { return 'View Hasil'; })->name('hasil.index')->middleware('permission:view hasil');
});

// Direktur Routes
Route::middleware(['auth', 'verified', 'role:direktur'])->prefix('direktur')->name('direktur.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['role' => 'Direktur']);
    })->name('dashboard')->middleware('permission:view dashboard');

    Route::get('/rekomendasi', function() { return 'View Rekomendasi'; })->name('rekomendasi.index')->middleware('permission:view rekomendasi');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wilayah API Routes (accessible by authenticated users)
    Route::prefix('api/wilayah')->group(function () {
        Route::get('/provinces', [WilayahController::class, 'provinces']);
        Route::get('/regencies/{province_id}', [WilayahController::class, 'regencies']);
        Route::get('/districts/{regency_id}', [WilayahController::class, 'districts']);
    });

    // BPS API Routes
    Route::prefix('api/bps')->group(function () {
        Route::get('/kepadatan-by-lokasi/{lokasi_id}', [\App\Http\Controllers\Api\BPSController::class, 'getKepadatanByLokasi']);
    });
});

require __DIR__.'/auth.php';
