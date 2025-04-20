<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tpa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat');
            $table->decimal('jarak', 10, 2); // dalam kilometer
            $table->decimal('biaya', 10, 2); // dalam rupiah
            $table->integer('skala_kemacetan');
            $table->decimal('kapasitas', 10, 2); // dalam hektare
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tpa');
    }
};
