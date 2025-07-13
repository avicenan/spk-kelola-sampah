<?php

namespace Database\Seeders;

use App\Models\SampahHarian;
use App\Models\JenisSampah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SampahHarianSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $jenisSampah = JenisSampah::where('is_active', true)->get();

        if (!$user || $jenisSampah->isEmpty()) {
            $this->command->info('No users or waste types found. Skipping SampahHarianSeeder.');
            return;
        }

        // Get specific waste types, with fallback to first available
        $sampahMakanan = $jenisSampah->where('nama', 'Sampah Makanan')->first() ?? $jenisSampah->first();
        $sampahPlastik = $jenisSampah->where('nama', 'Sampah Plastik')->first() ?? $jenisSampah->first();
        $sampahKertas = $jenisSampah->where('nama', 'Sampah Kertas')->first() ?? $jenisSampah->first();
        $sampahKaca = $jenisSampah->where('nama', 'Sampah Kaca')->first() ?? $jenisSampah->first();
        $sampahB3 = $jenisSampah->where('nama', 'Sampah B3 Hotel')->first() ?? $jenisSampah->first();
        $sampahTekstil = $jenisSampah->where('nama', 'Sampah Tekstil')->first() ?? $jenisSampah->first();

        $sampahData = [
            [
                'jenis_sampah_id' => $sampahMakanan->id,
                'volume_sampah' => 45.50,
                'sumber_sampah' => 'Dapur Hotel',
                'tanggal_input' => now()->subDays(5)
            ],
            [
                'jenis_sampah_id' => $sampahPlastik->id,
                'volume_sampah' => 32.75,
                'sumber_sampah' => 'Restoran',
                'tanggal_input' => now()->subDays(5)
            ],
            [
                'jenis_sampah_id' => $sampahKertas->id,
                'volume_sampah' => 28.25,
                'sumber_sampah' => 'Kantor',
                'tanggal_input' => now()->subDays(4)
            ],
            [
                'jenis_sampah_id' => $sampahMakanan->id,
                'volume_sampah' => 52.30,
                'sumber_sampah' => 'Dapur Hotel',
                'tanggal_input' => now()->subDays(4)
            ],
            [
                'jenis_sampah_id' => $sampahKaca->id,
                'volume_sampah' => 15.80,
                'sumber_sampah' => 'Bar',
                'tanggal_input' => now()->subDays(3)
            ],
            [
                'jenis_sampah_id' => $sampahPlastik->id,
                'volume_sampah' => 38.90,
                'sumber_sampah' => 'Restoran',
                'tanggal_input' => now()->subDays(3)
            ],
            [
                'jenis_sampah_id' => $sampahB3->id,
                'volume_sampah' => 8.50,
                'sumber_sampah' => 'Housekeeping',
                'tanggal_input' => now()->subDays(2)
            ],
            [
                'jenis_sampah_id' => $sampahMakanan->id,
                'volume_sampah' => 48.75,
                'sumber_sampah' => 'Dapur Hotel',
                'tanggal_input' => now()->subDays(2)
            ],
            [
                'jenis_sampah_id' => $sampahTekstil->id,
                'volume_sampah' => 12.30,
                'sumber_sampah' => 'Laundry',
                'tanggal_input' => now()->subDays(1)
            ],
            [
                'jenis_sampah_id' => $sampahPlastik->id,
                'volume_sampah' => 35.60,
                'sumber_sampah' => 'Restoran',
                'tanggal_input' => now()->subDays(1)
            ],
            [
                'jenis_sampah_id' => $sampahMakanan->id,
                'volume_sampah' => 50.25,
                'sumber_sampah' => 'Dapur Hotel',
                'tanggal_input' => now()
            ],
            [
                'jenis_sampah_id' => $sampahKertas->id,
                'volume_sampah' => 25.40,
                'sumber_sampah' => 'Kantor',
                'tanggal_input' => now()
            ]
        ];

        foreach ($sampahData as $data) {
            SampahHarian::create([
                'jenis_sampah_id' => $data['jenis_sampah_id'],
                'volume_sampah' => $data['volume_sampah'],
                'sumber_sampah' => $data['sumber_sampah'],
                'tanggal_input' => $data['tanggal_input'],
                'user_id' => $user->id
            ]);
        }

        $this->command->info('SampahHarianSeeder completed successfully.');
    }
}
