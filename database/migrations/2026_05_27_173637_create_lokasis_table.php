<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id('lokasi_id');
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->string('nama_lokasi');
            $table->text('alamat');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['kecamatan', 'kabupaten', 'provinsi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
