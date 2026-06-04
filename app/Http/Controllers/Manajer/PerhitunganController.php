<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\HasilPerhitungan;
use App\Services\TopsisService;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function index()
    {
        // View the latest calculation results (from DB)
        $hasil = HasilPerhitungan::with('penilaian.lokasi')
            ->orderBy('ranking', 'asc')
            ->get();

        // If we want to show steps, they would ideally be cached or stored in DB.
        // For now, we will recalculate in-memory just to display the steps 
        // to the user if they've just calculated.
        $steps = null;
        if (session()->has('topsis_steps')) {
            $steps = session()->get('topsis_steps');
        }

        return view('manajer.perhitungan.index', compact('hasil', 'steps'));
    }

    public function calculate(TopsisService $topsisService)
    {
        try {
            // Run the math!
            $steps = $topsisService->calculate();

            // Convert to array to prevent Eloquent serialization issues in session
            $stepsArray = json_decode(json_encode($steps), true);

            return redirect()->route('manajer.perhitungan.index')
                ->with('success', 'Perhitungan TOPSIS berhasil dilakukan!')
                ->with('topsis_steps', $stepsArray);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal melakukan perhitungan: ' . $e->getMessage());
        }
    }
}
