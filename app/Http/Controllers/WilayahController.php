<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function provinces(): JsonResponse
    {
        $provinces = Province::orderBy('name', 'asc')->get(['id', 'name']);
        return response()->json($provinces);
    }

    public function regencies($province_id): JsonResponse
    {
        $regencies = Regency::where('province_id', $province_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        return response()->json($regencies);
    }

    public function districts($regency_id): JsonResponse
    {
        $districts = District::where('regency_id', $regency_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
        return response()->json($districts);
    }

    public function getKepadatanByLokasi($lokasi_id): JsonResponse
    {
        $lokasi = Lokasi::find($lokasi_id);

        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        }

        // We stored the string names in 'kabupaten' and 'kecamatan'
        $kabupaten = strtolower(trim($lokasi->kabupaten));
        $kecamatan = strtolower(trim($lokasi->kecamatan));

        $stat = DB::table('population_statistics')
            ->where('regency_name', $kabupaten)
            ->where('district_name', $kecamatan)
            ->first();

        if ($stat) {
            return response()->json([
                'success' => true,
                'data' => [
                    'kepadatan' => $stat->kepadatan_penduduk,
                    'tahun' => date('Y'), // Based on local dataset time
                    'kode_bps' => 'LOCAL-DB'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data Kepadatan Penduduk tidak tersedia di database lokal'
        ], 200); // return 200 so UI falls back gracefully without red console errors
    }
}
