<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BPSService
{
    /**
     * Retrieve Kepadatan Penduduk for a given regency ID.
     * 
     * @param string $regencyId (E.g. "3273" for Kota Bandung)
     * @return array|null ['kepadatan' => value, 'tahun' => year, 'kode_bps' => domain]
     */
    public function getKepadatanPenduduk($regencyId)
    {
        $appId = config('services.bps.app_id');
        $varId = config('services.bps.var_kepadatan', 142);

        if (!$appId) {
            Log::warning('BPS_APP_ID is not configured.');
            return null;
        }

        // Clean the regency_id in case it contains a dot (Mendagri format)
        $domainId = str_replace('.', '', $regencyId);

        $cacheKey = "bps_kepadatan_{$domainId}_{$varId}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($domainId, $varId, $appId) {
            try {
                // Endpoint: https://webapi.bps.go.id/v1/api/list/model/data/domain/0000/var/xxx/key/[key]
                $url = "https://webapi.bps.go.id/v1/api/list/model/data/domain/{$domainId}/var/{$varId}/key/{$appId}";

                $response = Http::timeout(5)
                    ->retry(2, 100)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'application/json'
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // The BPS API returns "data-availability" and "datacontent"
                    // Often, we just extract the first available data point
                    if (isset($data['datacontent']) && is_array($data['datacontent']) && count($data['datacontent']) > 0) {
                        // Extract the value (could be an array of values per year/turvar)
                        // BPS data is usually flat key-value pairs or structured with ID keys.
                        // For simplicity, we just grab the first value we find.
                        $firstKey = array_key_first($data['datacontent']);
                        $kepadatan = $data['datacontent'][$firstKey];

                        // Attempt to extract year from data if available, otherwise default to current year
                        $tahun = isset($data['var'][0]['tahun']) ? $data['var'][0]['tahun'] : date('Y');

                        return [
                            'kepadatan' => (int) $kepadatan,
                            'tahun' => (int) $tahun,
                            'kode_bps' => $domainId,
                        ];
                    }
                    
                    Log::info("BPS API returned empty datacontent for domain {$domainId}");
                    return null;
                }

                Log::error("BPS API Request Failed: " . $response->body());
                return null;

            } catch (\Exception $e) {
                Log::error("BPS API Exception: " . $e->getMessage());
                return null;
            }
        });
    }
}
