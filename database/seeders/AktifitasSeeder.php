<?php

namespace Database\Seeders;

use App\Models\Aktifitas;
use App\Models\User;
use App\Models\Keputusan;
use Illuminate\Database\Seeder;

class AktifitasSeeder extends Seeder
{
    public function run()
    {
        // Get first user and Keputusan
        $user = User::first();
        $keputusan = Keputusan::first();

        if ($user && $keputusan) {
            $aktifitas = [
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Menambahkan data TPA Bantar Gebang ke dalam sistem.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Menambahkan data TPA Sumur Batu ke dalam sistem.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Menambahkan data TPA Cipayung ke dalam sistem.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Menambahkan data TPA Cilowong ke dalam sistem.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Membuat keputusan pengiriman sampah ke TPA Bantar Gebang.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Membuat keputusan pengiriman sampah ke TPA Sumur Batu.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Membuat keputusan pengiriman sampah ke TPA Cipayung.'
                ],
                [
                    'user_id' => $user->id,
                    'keputusan_id' => $keputusan->id,
                    'deskripsi' => 'Membuat keputusan pengiriman sampah ke TPA Cilowong.'
                ]
            ];

            foreach ($aktifitas as $data) {
                Aktifitas::create($data);
            }
        }
    }
}
