<?php

namespace Database\Seeders;

use App\Models\TPA;
use Illuminate\Database\Seeder;

class TPASeeder extends Seeder
{
    public function run()
    {
        $tpa = [
            [
                'nama' => 'TPA Bantar Gebang',
                'alamat' => 'Jl. Raya Bantar Gebang, Bekasi',
                'jarak' => 25.5,
                'kontak' => '021-88888888',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Sumur Batu',
                'alamat' => 'Jl. Raya Sumur Batu, Jakarta Timur',
                'jarak' => 15.2,
                'kontak' => '021-77777777',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cipayung',
                'alamat' => 'Jl. Raya Cipayung, Depok',
                'jarak' => 20.8,
                'kontak' => '021-66666666',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cilowong',
                'alamat' => 'Jl. Raya Cilowong, Serang',
                'jarak' => 35.0,
                'kontak' => '0254-555555',
                'is_active' => true
            ]
        ];

        foreach ($tpa as $data) {
            TPA::create($data);
        }
    }
}
