<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}
