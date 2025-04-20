<?php

namespace Database\Seeders;

use App\Models\Keputusan;
use App\Models\TPA;
use App\Models\User;
use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class KeputusanSeeder extends Seeder
{
    public function run()
    {
        // Get first user, TPAs, and Jenis Sampah
        $user = User::first();
        $tpaBantarGebang = TPA::where('nama', 'TPA Bantar Gebang')->first();
        $tpaSumurBatu = TPA::where('nama', 'TPA Sumur Batu')->first();
        $tpaCipayung = TPA::where('nama', 'TPA Cipayung')->first();
        $tpaCilowong = TPA::where('nama', 'TPA Cilowong')->first();

        $sampahOrganik = JenisSampah::where('nama', 'Sampah Makanan')->first();
        $sampahPlastik = JenisSampah::where('nama', 'Sampah Plastik')->first();
        $sampahKertas = JenisSampah::where('nama', 'Sampah Kertas')->first();
        $sampahB3 = JenisSampah::where('nama', 'Sampah B3 Hotel')->first();

        if ($user && $tpaBantarGebang && $tpaSumurBatu && $tpaCipayung && $tpaCilowong) {
            $keputusan = [
                [
                    'user_id' => $user->id,
                    'tpa_id' => $tpaBantarGebang->id,
                    'jenis_sampah_id' => $sampahOrganik->id,
                    'berat' => 150.50,
                    'judul' => 'Pengiriman Sampah Organik ke Bantar Gebang',
                    'isi' => 'Memutuskan untuk mengirim 150.50 kg sampah organik ke TPA Bantar Gebang karena memiliki fasilitas pengolahan sampah organik yang memadai.'
                ],
                [
                    'user_id' => $user->id,
                    'tpa_id' => $tpaSumurBatu->id,
                    'jenis_sampah_id' => $sampahPlastik->id,
                    'berat' => 75.25,
                    'judul' => 'Pengiriman Sampah Plastik ke Sumur Batu',
                    'isi' => 'Memutuskan untuk mengirim 75.25 kg sampah plastik ke TPA Sumur Batu karena memiliki fasilitas daur ulang yang baik.'
                ],
                [
                    'user_id' => $user->id,
                    'tpa_id' => $tpaCipayung->id,
                    'jenis_sampah_id' => $sampahB3->id,
                    'berat' => 25.00,
                    'judul' => 'Pengiriman Sampah B3 ke Cipayung',
                    'isi' => 'Memutuskan untuk mengirim 25.00 kg sampah B3 ke TPA Cipayung karena memiliki fasilitas pengolahan sampah B3 yang sesuai standar.'
                ],
                [
                    'user_id' => $user->id,
                    'tpa_id' => $tpaCilowong->id,
                    'jenis_sampah_id' => $sampahKertas->id,
                    'berat' => 100.75,
                    'judul' => 'Pengiriman Sampah Kertas ke Cilowong',
                    'isi' => 'Memutuskan untuk mengirim 100.75 kg sampah kertas ke TPA Cilowong sebagai alternatif TPA utama.'
                ]
            ];

            foreach ($keputusan as $data) {
                Keputusan::create($data);
            }
        }
    }
}
