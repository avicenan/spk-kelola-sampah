<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('keputusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // user yang membuat keputusan
            $table->foreignId('tpa_id')->constrained('tpa');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah');
            $table->decimal('berat', 10, 2); // berat sampah dalam kg
            $table->integer('biaya'); // dalam rupiah
            $table->integer('tingkat_kemacetan'); // min1 max 5
            $table->string('judul');
            $table->text('isi');
            $table->date('from');
            $table->date('to');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keputusan');
    }
};
