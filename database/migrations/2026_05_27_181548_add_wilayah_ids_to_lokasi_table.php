<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->string('province_id', 10)->nullable()->after('provinsi');
            $table->string('regency_id', 10)->nullable()->after('kabupaten');
            $table->string('district_id', 10)->nullable()->after('kecamatan');
            
            // Note: We don't enforce strict foreign keys here in case old data has no matching IDs 
            // and because we want to allow graceful fallbacks. 
            // BPS regions change over time, so loose coupling on Lokasi is safer.
        });
    }

    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'regency_id', 'district_id']);
        });
    }
};
