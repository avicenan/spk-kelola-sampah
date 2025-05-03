<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aktifitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('keputusan_id')->nullable()->constrained('keputusan');
            $table->enum('jenis', ['add_keputusan', 'add_tpa', 'edit_tpa', 'delete_tpa', 'add_jenis_sampah', 'edit_jenis_sampah', 'delete_jenis_sampah', 'add_kriteria', 'edit_kriteria', 'delete_kriteria', 'add_tpa_jenis_sampah', 'edit_tpa_jenis_sampah', 'delete_tpa_jenis_sampah', 'add_tpa_kriteria', 'edit_tpa_kriteria', 'delete_tpa_kriteria', 'register', 'login', 'logout']);
            $table->text('deskripsi')->nullable(); // Deskripsi aktivitas
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aktifitas');
    }
};
