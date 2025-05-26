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
            ['label' => 'Jarak', 'nama' => 'jarak', 'sifat' => 'cost', 'bobot' => 0.3, 'satuan_ukur' => 'km', 'is_deletable' => false],
            ['label' => 'Biaya', 'nama' => 'biaya', 'sifat' => 'cost', 'bobot' => 0.4, 'satuan_ukur' => 'Rp', 'is_deletable' => false],
            ['label' => 'Tingkat Kemacetan', 'nama' => 'tingkat_kemacetan', 'sifat' => 'cost', 'bobot' => 0.1, 'satuan_ukur' => '1-5', 'is_deletable' => false],
        ];

        foreach ($data as $d) {
            Kriteria::create($d);
        }
    }
}
