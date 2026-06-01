<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Services\BPSService;
use Illuminate\Http\Request;

class BPSController extends Controller
{
    protected $bpsService;

    public function __construct(BPSService $bpsService)
    {
        $this->bpsService = $bpsService;
    }

    public function getKepadatanByLokasi(Request $request, $lokasi_id)
    {
        $lokasi = Lokasi::find($lokasi_id);
        
        if (!$lokasi) {
            return response()->json(['success' => false, 'message' => 'Lokasi tidak ditemukan'], 404);
        }

        if (!$lokasi->regency_id) {
            return response()->json(['success' => false, 'message' => 'Lokasi belum memiliki ID Kabupaten'], 400);
        }

        $data = $this->bpsService->getKepadatanPenduduk($lokasi->regency_id);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data dari BPS'
        ], 200);
    }
}
