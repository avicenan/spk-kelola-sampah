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
                'biaya' => 50000,
                'skala_kemacetan' => 3,
                'kapasitas' => 100.00,
                'is_active' => true
            ],
            [
                'nama' => 'TPA Sumur Batu',
                'alamat' => 'Jl. Raya Sumur Batu, Jakarta Timur',
                'jarak' => 15.2,
                'biaya' => 45000,
                'skala_kemacetan' => 2,
                'kapasitas' => 75.50,
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cipayung',
                'alamat' => 'Jl. Raya Cipayung, Depok',
                'jarak' => 20.8,
                'biaya' => 48000,
                'skala_kemacetan' => 2,
                'kapasitas' => 85.25,
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cilowong',
                'alamat' => 'Jl. Raya Cilowong, Serang',
                'jarak' => 35.0,
                'biaya' => 55000,
                'skala_kemacetan' => 1,
                'kapasitas' => 90.00,
                'is_active' => true
            ]
        ];

        foreach ($tpa as $data) {
            TPA::create($data);
        }
    }
}
