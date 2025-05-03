<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_keputusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keputusan_id')->constrained('keputusan');
            $table->integer('rank');
            $table->string('nama');
            $table->text('alamat');
            $table->string('kontak');
            $table->float('jarak');
            $table->float('biaya');
            $table->integer('tingkat_kemacetan');
            $table->string('jenis_sampah');
            $table->string('sumber_sampah');
            $table->date('from');
            $table->date('to');
            $table->float('jumlah_sampah');
            $table->string('nama_pengguna');
            $table->string('email_pengguna');
            $table->string('role');
            $table->date('created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_keputusans');
    }
};
