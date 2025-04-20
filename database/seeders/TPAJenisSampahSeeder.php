<?php

namespace Database\Seeders;

use App\Models\TPA;
use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class TPAJenisSampahSeeder extends Seeder
{
    public function run()
    {
        // Get TPAs
        $tpaBantarGebang = TPA::where('nama', 'TPA Bantar Gebang')->first();
        $tpaSumurBatu = TPA::where('nama', 'TPA Sumur Batu')->first();
        $tpaCipayung = TPA::where('nama', 'TPA Cipayung')->first();
        $tpaCilowong = TPA::where('nama', 'TPA Cilowong')->first();

        // Get Jenis Sampah
        $sampahMakanan = JenisSampah::where('nama', 'Sampah Makanan')->first();
        $sampahKertas = JenisSampah::where('nama', 'Sampah Kertas')->first();
        $sampahPlastik = JenisSampah::where('nama', 'Sampah Plastik')->first();
        $sampahKaca = JenisSampah::where('nama', 'Sampah Kaca')->first();
        $sampahB3 = JenisSampah::where('nama', 'Sampah B3 Hotel')->first();
        $sampahTekstil = JenisSampah::where('nama', 'Sampah Tekstil')->first();
        $sampahElektronik = JenisSampah::where('nama', 'Sampah Elektronik')->first();

        // Bantar Gebang - handles all types of waste
        if ($tpaBantarGebang) {
            $tpaBantarGebang->jenisSampah()->sync([
                $sampahMakanan->id => ['is_active' => true],
                $sampahKertas->id => ['is_active' => true],
                $sampahPlastik->id => ['is_active' => true],
                $sampahKaca->id => ['is_active' => true],
                $sampahB3->id => ['is_active' => true],
                $sampahTekstil->id => ['is_active' => true],
                $sampahElektronik->id => ['is_active' => true]
            ]);
        }

        // Sumur Batu - specializes in recyclables
        if ($tpaSumurBatu) {
            $tpaSumurBatu->jenisSampah()->sync([
                $sampahKertas->id => ['is_active' => true],
                $sampahPlastik->id => ['is_active' => true],
                $sampahKaca->id => ['is_active' => true]
            ]);
        }

        // Cipayung - specializes in B3 and electronic waste
        if ($tpaCipayung) {
            $tpaCipayung->jenisSampah()->sync([
                $sampahB3->id => ['is_active' => true],
                $sampahElektronik->id => ['is_active' => true]
            ]);
        }

        // Cilowong - handles general waste and textiles
        if ($tpaCilowong) {
            $tpaCilowong->jenisSampah()->sync([
                $sampahMakanan->id => ['is_active' => true],
                $sampahKertas->id => ['is_active' => true],
                $sampahPlastik->id => ['is_active' => true],
                $sampahTekstil->id => ['is_active' => true]
            ]);
        }
    }
}
