<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\TPA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TPAKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriterias = Kriteria::all();

        foreach (TPA::all() as $tpa) {
            $tpa->kriterias()->sync($kriterias->pluck('id')->toArray());
        }
    }
}
