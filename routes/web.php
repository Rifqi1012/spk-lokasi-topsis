<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\Manajer\DashboardController as ManajerDashboardController;
use App\Http\Controllers\Manajer\HasilController as ManajerHasilController;
use App\Http\Controllers\Direktur\DashboardController as DirekturDashboardController;
use App\Http\Controllers\Direktur\RekomendasiController as DirekturRekomendasiController;
use App\Http\Controllers\Direktur\ObservasiController as DirekturObservasiController;
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
    Route::get('/dashboard', [ManajerDashboardController::class, 'index'])->name('dashboard');

    // Users CRUD
    Route::resource('users', \App\Http\Controllers\Manajer\UserController::class);
    // Kriteria Routes (Restricted: no create, store, or destroy)
    Route::resource('kriteria', \App\Http\Controllers\Manajer\KriteriaController::class)->only(['index', 'edit', 'update'])->parameters([
        'kriteria' => 'kriteria' // prevent laravel from guessing 'kriterium'
    ]);
    // Lokasi CRUD
    Route::resource('lokasi', \App\Http\Controllers\Manajer\LokasiController::class);
    // Observasi CRUD
    Route::get('observasi/create/{lokasi}', [\App\Http\Controllers\Manajer\ObservasiController::class, 'create'])->name('observasi.create');
    Route::resource('observasi', \App\Http\Controllers\Manajer\ObservasiController::class)->except(['create', 'edit', 'update']);
    // Penilaian & Perhitungan TOPSIS
    Route::get('/penilaian', [\App\Http\Controllers\Manajer\PenilaianController::class, 'index'])->name('penilaian.index')->middleware('permission:manage penilaian');
    Route::get('/perhitungan', [\App\Http\Controllers\Manajer\PerhitunganController::class, 'index'])->name('perhitungan.index')->middleware('permission:process perhitungan');
    Route::post('/perhitungan/calculate', [\App\Http\Controllers\Manajer\PerhitunganController::class, 'calculate'])->name('perhitungan.calculate')->middleware('permission:process perhitungan');
    Route::get('/perhitungan/export/excel', [\App\Http\Controllers\Manajer\PerhitunganController::class, 'exportExcel'])->name('perhitungan.export.excel')->middleware('permission:process perhitungan');

    // Hasil Keputusan (Final Business Decision)
    Route::get('/hasil', [ManajerHasilController::class, 'index'])->name('hasil.index')->middleware('permission:view hasil');
    Route::get('/hasil/export/pdf', [ManajerHasilController::class, 'exportPdf'])->name('hasil.export.pdf')->middleware('permission:view hasil');
});

// Direktur Routes
Route::middleware(['auth', 'verified', 'role:direktur'])->prefix('direktur')->name('direktur.')->group(function () {
    Route::get('/dashboard', [DirekturDashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view dashboard');

    Route::prefix('rekomendasi')->name('rekomendasi.')->group(function() {
        Route::get('/', [DirekturRekomendasiController::class, 'index'])->name('index');
        Route::get('/export/pdf', [DirekturRekomendasiController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [DirekturRekomendasiController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}', [DirekturRekomendasiController::class, 'show'])->name('show');
    });

    Route::prefix('observasi')->name('observasi.')->group(function() {
        Route::get('/', [DirekturObservasiController::class, 'index'])->name('index');
        Route::get('/{id}', [DirekturObservasiController::class, 'show'])->name('show');
    });
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
        Route::get('/kepadatan-by-lokasi/{lokasi_id}', [WilayahController::class, 'getKepadatanByLokasi']);
    });
});

require __DIR__.'/auth.php';
