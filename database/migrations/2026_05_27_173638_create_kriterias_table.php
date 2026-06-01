<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id('kriteria_id');
            $table->string('kode_kriteria')->unique();
            $table->string('nama_kriteria');
            $table->decimal('bobot', 5, 2);
            $table->enum('atribut', ['benefit', 'cost']);
            $table->enum('jenis_input', ['numeric', 'scoring']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
