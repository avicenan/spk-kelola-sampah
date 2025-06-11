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
            $table->float('skor');
            $table->string('nama');
            $table->text('alamat');
            $table->string('kontak');
            $table->string('jenis_sampah');
            $table->string('sumber_sampah');
            $table->date('from');
            $table->date('to');
            $table->integer('jumlah_sampah');
            $table->string('nama_pengguna');
            $table->string('email_pengguna');
            $table->string('role');
            $table->json('kriterias');
            // $table->datetime('created_at');
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
