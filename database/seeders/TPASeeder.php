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
                'kontak' => '021-88888888',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Sumur Batu',
                'alamat' => 'Jl. Raya Sumur Batu, Jakarta Timur',
                'kontak' => '021-77777777',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cipayung',
                'alamat' => 'Jl. Raya Cipayung, Depok',
                'kontak' => '021-66666666',
                'is_active' => true
            ],
            [
                'nama' => 'TPA Cilowong',
                'alamat' => 'Jl. Raya Cilowong, Serang',
                'kontak' => '0254-555555',
                'is_active' => true
            ]
        ];

        foreach ($tpa as $data) {
            TPA::create($data);
        }
    }
}
