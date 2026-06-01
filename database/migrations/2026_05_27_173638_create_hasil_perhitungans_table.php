<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_perhitungan', function (Blueprint $table) {
            $table->id('hasil_id');
            $table->foreignId('penilaian_id')->constrained('penilaian', 'penilaian_id')->cascadeOnDelete();
            $table->decimal('nilai_preferensi', 12, 6);
            $table->integer('ranking');
            $table->timestamp('tanggal_hitung');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_perhitungan');
    }
};
