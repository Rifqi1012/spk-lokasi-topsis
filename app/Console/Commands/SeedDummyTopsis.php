<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedDummyTopsis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed dummy data for TOPSIS calculation testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Menjalankan seeder data dummy TOPSIS...');
        
        Artisan::call('db:seed', [
            '--class' => 'DummyTopsisSeeder'
        ], $this->output);
        
        $this->info('Data dummy berhasil dimasukkan! Silakan cek perhitungan TOPSIS di aplikasi.');
    }
}
