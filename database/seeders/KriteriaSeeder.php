<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['label' => 'Jarak', 'nama' => 'jarak', 'sifat' => 'cost', 'bobot' => 0.3],
            ['label' => 'Biaya', 'nama' => 'biaya', 'sifat' => 'cost', 'bobot' => 0.4],
            ['label' => 'Tingkat Kemacetan', 'nama' => 'tingkat_kemacetan', 'sifat' => 'cost', 'bobot' => 0.1]
        ];

        foreach ($data as $d) {
            Kriteria::create($d);
        }
    }
}
