<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class ImportWilayahPopulation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wilayah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import local wilayah and population density from CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = base_path('kepadatan_penduduk.csv');
        if (!file_exists($file)) {
            $this->error("File kepadatan_penduduk.csv not found in project root.");
            return;
        }

        $this->info("Importing Wilayah and Population Density data...");

        // Parse CSV
        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv); // Remove header (daerah,nama kecamatan,kepadatan penduduk)

        // 1. Clear existing tables (since we are fully switching to local dataset)
        // Truncate causes implicit commit in MySQL, so do it outside transaction
        $this->info("Clearing old data...");
        DB::table('population_statistics')->truncate();
        
        DB::beginTransaction();
        try {
            District::query()->delete();
            Regency::query()->delete();
            Province::query()->delete();

            // 2. Create single unified Province
            $province = Province::create([
                'id' => '1',
                'name' => 'Jawa & Sekitarnya'
            ]);

            $regenciesMap = []; // cache regency id by name
            $regencyCounter = 1;
            $districtCounter = 1;

            $this->output->progressStart(count($csv));

            foreach ($csv as $row) {
                if (count($row) < 3) continue;

                $daerah = trim($row[0]);
                $kecamatan = trim($row[1]);
                $kepadatan_raw = $row[2];

                // Convert '2.885' (using dot as thousand separator in ID) or standard floats
                // Actually in the CSV some values are like '2.885' which means 2,885.
                // Or if it's float? Looking at the CSV: 2.885, 3.409. In Indonesia dot is thousand separator.
                // Let's strip dot if it's there. 
                $kepadatan = str_replace('.', '', trim($kepadatan_raw));
                $kepadatan = (float) $kepadatan;

                $regencyKey = strtolower($daerah);
                if (!isset($regenciesMap[$regencyKey])) {
                    $regency = Regency::create([
                        'id' => 'R' . $regencyCounter++,
                        'province_id' => $province->id,
                        'name' => strtoupper($daerah)
                    ]);
                    $regenciesMap[$regencyKey] = $regency->id;
                }
                
                $regencyId = $regenciesMap[$regencyKey];

                $district = District::create([
                    'id' => 'D' . $districtCounter++,
                    'regency_id' => $regencyId,
                    'name' => strtoupper($kecamatan)
                ]);

                DB::table('population_statistics')->insert([
                    'regency_name' => strtolower($daerah),
                    'district_name' => strtolower($kecamatan),
                    'kepadatan_penduduk' => $kepadatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            DB::commit();

            $this->info("Successfully imported all Wilayah and Population Density data!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to import data: " . $e->getMessage());
        }
    }
}
