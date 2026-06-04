<?php

namespace Database\Seeders;

use App\Models\DetailPenilaian;
use App\Models\Kriteria;
use App\Models\Lokasi;
use App\Models\ObservasiLokasi;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyTopsisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mulai melakukan seeding data dummy TOPSIS...');

        // Get the first user to assign as creator (assuming admin exists)
        $user = User::first();
        $userId = $user ? $user->id : 1;

        // Fetch criteria dynamically ordered by 'urutan'
        // This ensures we do not hardcode 'kode_kriteria' or 'kriteria_id'
        $kriterias = Kriteria::orderBy('urutan')->get();

        if ($kriterias->count() < 6) {
            $this->command->error('Kriteria kurang dari 6. Pastikan KriteriaSeeder telah dijalankan.');
            return;
        }

        /* 
         * DATASET EXCEL
         * Scores array order matches criteria 'urutan' 1 to 6:
         * 1. Kepadatan Penduduk (C1)
         * 2. Aksesibilitas (C2)
         * 3. Biaya Sewa (C3)
         * 4. Jumlah Kompetitor (C4)
         * 5. Jarak Dengan RPH (C5)
         * 6. Kelayakan Bangunan (C6)
         */
        $dataset = [
            [
                'nama' => 'Kp Sarampad',
                'scores' => [1652, 3, 11000000, 1, 0.37, 3]
            ],
            [
                'nama' => 'Jl. Perumahan Puncak Manis',
                'scores' => [6820, 5, 11400000, 3, 2.3, 3]
            ],
            [
                'nama' => 'Jl Sabandar',
                'scores' => [3726, 5, 23000000, 4, 3.5, 3]
            ],
            [
                'nama' => 'Jl. Griya Maleber Indah',
                'scores' => [3726, 5, 8000000, 2, 2.8, 1]
            ],
            [
                'nama' => 'Jl. Bumi Emas',
                'scores' => [2437, 5, 12000000, 2, 1.2, 5]
            ],
            [
                'nama' => 'Jl Raya Cibeber, Kp Cilaku Kaum',
                'scores' => [2437, 5, 16000000, 2, 1.7, 5]
            ],
            [
                'nama' => 'Ciputri',
                'scores' => [2837, 1, 12000000, 1, 4.1, 5]
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ($dataset as $data) {
                // 1. Create Lokasi
                $lokasi = Lokasi::create([
                    'created_by' => $userId,
                    'nama_lokasi' => $data['nama'],
                    'alamat' => 'Alamat Dummy ' . $data['nama'],
                    // Minimal required fields
                    'provinsi' => 'Jawa Barat',
                    'kabupaten' => 'Cianjur',
                    'kecamatan' => 'Cianjur',
                ]);

                // 2. Create ObservasiLokasi
                // Populating minimum required dummy fields with mapping to the scores where applicable
                $observasi = ObservasiLokasi::create([
                    'lokasi_id' => $lokasi->lokasi_id,
                    'user_id' => $userId,
                    'jenis_bangunan' => 'Ruko',
                    'luas_tanah' => 100,
                    'luas_bangunan' => 100,
                    'jumlah_ruangan' => 2,
                    'jumlah_wc' => 1,
                    'listrik' => true,
                    'sumber_air' => 'PDAM',
                    'harga_sewa' => $data['scores'][2], // C3 = Biaya Sewa
                    'jarak_rph' => $data['scores'][4], // C5 = Jarak RPH
                    'jumlah_kompetitor' => $data['scores'][3], // C4 = Jumlah Kompetitor
                    'kepadatan_penduduk' => $data['scores'][0], // C1 = Kepadatan Penduduk
                    'akses_roda4' => true,
                    'jalan_bagus' => true,
                    'dekat_fasilitas' => true,
                    'bangunan_layak' => true,
                    'ventilasi_baik' => true,
                    'air_listrik_memadai' => true,
                    'catatan' => 'Dummy record for testing',
                    'tanggal_observasi' => now(),
                ]);

                // 3. Create Penilaian
                $penilaian = Penilaian::create([
                    'lokasi_id' => $lokasi->lokasi_id,
                    'observasi_id' => $observasi->observasi_id,
                    'user_id' => $userId,
                    'tanggal_penilaian' => now(),
                ]);

                // 4. Create Detail Penilaian dynamically
                foreach ($data['scores'] as $index => $score) {
                    $kriteria = $kriterias[$index]; // Map index (0-5) to ordered criteria

                    DetailPenilaian::create([
                        'penilaian_id' => $penilaian->penilaian_id,
                        'kriteria_id' => $kriteria->kriteria_id,
                        // Ensure values like 0.37 stay as proper floats/decimals
                        'nilai' => (float)$score, 
                    ]);
                }
            }

            DB::commit();
            $this->command->info('Seeding data dummy TOPSIS berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal melakukan seeding: ' . $e->getMessage());
        }
    }
}
