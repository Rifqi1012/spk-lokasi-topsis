<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;

class SyncWilayahCommand extends Command
{
    protected $signature = 'wilayah:sync';
    protected $description = 'Sync Indonesian regions from EMSIFA API to local database';

    const BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function handle()
    {
        $this->info('Starting Wilayah Synchronization...');

        DB::beginTransaction();

        try {
            // 1. Sync Provinces
            $this->info('Fetching Provinces...');
            $response = Http::timeout(30)->get(self::BASE_URL . '/provinces.json');
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch provinces.');
            }

            $provinces = $response->json();
            $provinceCount = count($provinces);
            
            $provinceData = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $provinces);

            Province::upsert($provinceData, ['id'], ['name', 'updated_at']);
            $this->info("Synced $provinceCount provinces.");

            // 2. Sync Regencies & 3. Sync Districts
            $this->info('Fetching Regencies and Districts (this may take a few minutes)...');
            
            $bar = $this->output->createProgressBar($provinceCount);
            $bar->start();

            foreach ($provinces as $province) {
                // Fetch Regencies for this Province
                $regResponse = Http::timeout(30)->get(self::BASE_URL . "/regencies/{$province['id']}.json");
                if ($regResponse->successful()) {
                    $regencies = $regResponse->json();
                    
                    $regencyData = array_map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'province_id' => $item['province_id'],
                            'name' => $item['name'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }, $regencies);

                    // Chunk insert Regencies
                    foreach (array_chunk($regencyData, 500) as $chunk) {
                        Regency::upsert($chunk, ['id'], ['province_id', 'name', 'updated_at']);
                    }

                    // For each Regency, fetch Districts
                    foreach ($regencies as $regency) {
                        $distResponse = Http::timeout(30)->get(self::BASE_URL . "/districts/{$regency['id']}.json");
                        if ($distResponse->successful()) {
                            $districts = $distResponse->json();
                            
                            $districtData = array_map(function ($item) {
                                return [
                                    'id' => $item['id'],
                                    'regency_id' => $item['regency_id'],
                                    'name' => $item['name'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }, $districts);

                            // Chunk insert Districts
                            foreach (array_chunk($districtData, 500) as $chunk) {
                                District::upsert($chunk, ['id'], ['regency_id', 'name', 'updated_at']);
                            }
                        }
                    }
                }
                
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            
            DB::commit();
            $this->info('Wilayah Synchronization completed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Synchronization failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
