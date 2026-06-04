<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\HasilPerhitungan;
use App\Models\Kriteria;
use App\Models\Lokasi;
use App\Models\ObservasiLokasi;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLokasi = Lokasi::count();
        $lokasiDinilai = Lokasi::whereHas('penilaians')->count();
        $lokasiBelumDinilai = Lokasi::whereDoesntHave('penilaians')->count();
        
        $kriteriaBenefit = Kriteria::where('atribut', 'benefit')->count();
        $kriteriaCost = Kriteria::where('atribut', 'cost')->count();

        $topRanking = HasilPerhitungan::with('penilaian.lokasi')
            ->orderBy('ranking', 'asc')
            ->get();

        $lokasiTerbaik = $topRanking->first();
        $top3 = $topRanking->take(3);
        $lastCalculation = HasilPerhitungan::max('tanggal_hitung');

        $chartLabels = [];
        $chartData = [];
        foreach ($topRanking->take(5) as $rank) {
            $chartLabels[] = $rank->penilaian->lokasi->nama_lokasi ?? 'Unknown';
            $chartData[] = round($rank->nilai_preferensi, 4);
        }

        $totalKriteriaAktif = Kriteria::count();
        $totalObservasi = ObservasiLokasi::count();
        $totalHasilPerhitungan = HasilPerhitungan::count();

        $statusMessage = 'Semua data siap untuk proses TOPSIS';
        $statusType = 'success'; // success, warning, error

        if ($lokasiBelumDinilai > 0) {
            $statusMessage = "{$lokasiBelumDinilai} lokasi belum memiliki penilaian lengkap.";
            $statusType = 'warning';
        } elseif ($totalLokasi === 0 || $totalKriteriaAktif === 0) {
            $statusMessage = "Data kriteria atau lokasi belum lengkap.";
            $statusType = 'error';
        }

        return view('dashboard', compact(
            'totalLokasi',
            'lokasiDinilai',
            'lokasiBelumDinilai',
            'kriteriaBenefit',
            'kriteriaCost',
            'lokasiTerbaik',
            'top3',
            'lastCalculation',
            'chartLabels',
            'chartData',
            'totalKriteriaAktif',
            'totalObservasi',
            'totalHasilPerhitungan',
            'statusMessage',
            'statusType'
        ));
    }
}
