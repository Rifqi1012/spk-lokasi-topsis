<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = [
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Kepadatan Penduduk',
                'bobot' => 25.00,
                'atribut' => 'benefit',
                'jenis_input' => 'numeric',
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Biaya Sewa',
                'bobot' => 20.00,
                'atribut' => 'cost',
                'jenis_input' => 'numeric',
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Jumlah Kompetitor',
                'bobot' => 15.00,
                'atribut' => 'cost',
                'jenis_input' => 'numeric',
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Jarak dengan RPH',
                'bobot' => 15.00,
                'atribut' => 'cost',
                'jenis_input' => 'numeric',
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'Aksesibilitas',
                'bobot' => 15.00,
                'atribut' => 'benefit',
                'jenis_input' => 'scoring',
            ],
            [
                'kode_kriteria' => 'C6',
                'nama_kriteria' => 'Kelayakan Bangunan',
                'bobot' => 10.00,
                'atribut' => 'benefit',
                'jenis_input' => 'scoring',
            ]
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
