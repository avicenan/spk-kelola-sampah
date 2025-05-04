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
        $user = User::first();

        $keputusan = [
            [
                'user_id' => $user->id,
                'judul' => 'Pengiriman Sampah Organik ke Bantar Gebang',
                'keterangan' => 'Memutuskan untuk mengirim 150.50 kg sampah organik ke TPA Bantar Gebang karena memiliki fasilitas pengolahan sampah organik yang memadai.',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Pengiriman Sampah Plastik ke Sumur Batu',
                'keterangan' => 'Memutuskan untuk mengirim 75.25 kg sampah plastik ke TPA Sumur Batu karena memiliki fasilitas daur ulang yang baik.',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Pengiriman Sampah B3 ke Cipayung',
                'keterangan' => 'Memutuskan untuk mengirim 25.00 kg sampah B3 ke TPA Cipayung karena memiliki fasilitas pengolahan sampah B3 yang sesuai standar.',
            ],
            [
                'user_id' => $user->id,
                'judul' => 'Pengiriman Sampah Kertas ke Cilowong',
                'keterangan' => 'Memutuskan untuk mengirim 100.75 kg sampah kertas ke TPA Cilowong sebagai alternatif TPA utama.',
            ]
        ];

        foreach ($keputusan as $data) {
            Keputusan::create($data);
        }
    }
}
