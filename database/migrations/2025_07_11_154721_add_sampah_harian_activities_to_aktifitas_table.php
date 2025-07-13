<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new enum values to the jenis column
        DB::statement("ALTER TABLE aktifitas MODIFY COLUMN jenis ENUM('add_keputusan', 'add_tpa', 'edit_tpa', 'delete_tpa', 'add_jenis_sampah', 'edit_jenis_sampah', 'delete_jenis_sampah', 'add_kriteria', 'edit_kriteria', 'delete_kriteria', 'add_tpa_jenis_sampah', 'edit_tpa_jenis_sampah', 'delete_tpa_jenis_sampah', 'add_tpa_kriteria', 'edit_tpa_kriteria', 'delete_tpa_kriteria', 'register', 'login', 'logout', 'add_sampah_harian', 'edit_sampah_harian', 'delete_sampah_harian')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the new enum values
        DB::statement("ALTER TABLE aktifitas MODIFY COLUMN jenis ENUM('add_keputusan', 'add_tpa', 'edit_tpa', 'delete_tpa', 'add_jenis_sampah', 'edit_jenis_sampah', 'delete_jenis_sampah', 'add_kriteria', 'edit_kriteria', 'delete_kriteria', 'add_tpa_jenis_sampah', 'edit_tpa_jenis_sampah', 'delete_tpa_jenis_sampah', 'add_tpa_kriteria', 'edit_tpa_kriteria', 'delete_tpa_kriteria', 'register', 'login', 'logout')");
    }
};
