<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisSampahSeeder::class,
            TPASeeder::class,
            KeputusanSeeder::class,
            AktifitasSeeder::class,
            TPAJenisSampahSeeder::class,
            KriteriaSeeder::class,
            TPAKriteriaSeeder::class
        ]);
    }
}
