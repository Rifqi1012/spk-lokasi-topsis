<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('population_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('regency_name')->index();
            $table->string('district_name')->index();
            $table->float('kepadatan_penduduk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('population_statistics');
    }
};
