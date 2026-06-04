<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\HasilPerhitungan;
use App\Models\Kriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HasilController extends Controller
{
    public function index()
    {
        $hasil = HasilPerhitungan::with(['penilaian.lokasi', 'penilaian.observasiLokasi'])
            ->orderBy('ranking', 'asc')
            ->get();

        $lokasiTerbaik = $hasil->first();
        $totalAlternatif = $hasil->count();
        $lastCalculationDate = HasilPerhitungan::max('tanggal_hitung');
        
        Carbon::setLocale('id');
        $lastCalculation = $lastCalculationDate 
            ? Carbon::parse($lastCalculationDate)->translatedFormat('d F Y - H:i') . ' WIB' 
            : '-';

        return view('manajer.hasil.index', compact(
            'hasil',
            'lokasiTerbaik',
            'totalAlternatif',
            'lastCalculation'
        ));
    }

    public function exportPdf()
    {
        $hasil = HasilPerhitungan::with(['penilaian.lokasi'])
            ->orderBy('ranking', 'asc')
            ->get();

        $kriteria = Kriteria::orderBy('display_order')->get();
        
        Carbon::setLocale('id');
        $timestamp = Carbon::now()->translatedFormat('d F Y - H:i') . ' WIB';

        $pdf = Pdf::loadView('manajer.hasil.pdf', compact('hasil', 'kriteria', 'timestamp'));
        
        return $pdf->download('Laporan_Hasil_Keputusan_TOPSIS_' . date('Ymd_His') . '.pdf');
    }
}
