<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Lokasi;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        // Get all active criteria for the table header, ordered by 'urutan'
        $kriterias = Kriteria::orderBy('urutan')->get();

        // Get all locations that have a valuation (Penilaian)
        // Eager load detailPenilaians to build the matrix efficiently
        $penilaians = Penilaian::with(['lokasi', 'detailPenilaians.kriteria'])
            ->whereHas('lokasi')
            ->get();

        $totalKriteria = $kriterias->count();
        $isComplete = true;

        // Build the matrix data structure
        $matrix = [];
        foreach ($penilaians as $penilaian) {
            $row = [
                'penilaian_id' => $penilaian->penilaian_id,
                'nama_lokasi' => $penilaian->lokasi->nama_lokasi,
                'details' => []
            ];

            // Initialize all criteria slots with null
            foreach ($kriterias as $k) {
                $row['details'][$k->kriteria_id] = null;
            }

            // Fill in the actual details
            $detailsCount = 0;
            foreach ($penilaian->detailPenilaians as $detail) {
                $row['details'][$detail->kriteria_id] = $detail->nilai;
                $detailsCount++;
            }

            // Check completeness for this row
            if ($detailsCount < $totalKriteria) {
                $isComplete = false;
            }

            $matrix[] = $row;
        }

        // If there are no penilaians at all, it's not "complete" enough to calculate
        if ($penilaians->isEmpty() || $totalKriteria === 0) {
            $isComplete = false;
        }

        return view('manajer.penilaian.index', compact('kriterias', 'matrix', 'isComplete'));
    }
}
