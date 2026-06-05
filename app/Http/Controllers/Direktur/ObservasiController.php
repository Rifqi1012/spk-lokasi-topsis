<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\ObservasiLokasi;
use App\Models\Penilaian;
use App\Models\HasilPerhitungan;
use Illuminate\Http\Request;

class ObservasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ObservasiLokasi::with(['lokasi', 'user']);

        if ($search) {
            $query->whereHas('lokasi', function($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%");
            });
        }

        $observasis = $query->orderBy('tanggal_observasi', 'desc')->paginate(10);

        return view('direktur.observasi.index', compact('observasis', 'search'));
    }

    public function show($id)
    {
        $observasi = ObservasiLokasi::with([
            'lokasi', 
            'user', 
            'dokumentasiLokasis',
            'penilaians'
        ])->findOrFail($id);

        $penilaian = Penilaian::where('observasi_id', $observasi->observasi_id)->first();
        $hasilTopsis = null;

        if ($penilaian) {
            $hasilTopsis = HasilPerhitungan::where('penilaian_id', $penilaian->penilaian_id)->first();
        }

        return view('direktur.observasi.show', compact('observasi', 'hasilTopsis'));
    }
}
