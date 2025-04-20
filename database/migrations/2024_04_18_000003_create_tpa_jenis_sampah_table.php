<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tpa_jenis_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpa_id')->constrained('tpa')->onDelete('cascade');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique combination of tpa_id and jenis_sampah_id
            $table->unique(['tpa_id', 'jenis_sampah_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tpa_jenis_sampah');
    }
};
