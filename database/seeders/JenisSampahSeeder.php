<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use Illuminate\Database\Seeder;

class JenisSampahSeeder extends Seeder
{
    public function run()
    {
        $jenisSampah = [
            [
                'nama' => 'Sampah Makanan',
                'sumber_sampah' => 'Dapur hotel, restoran, room service',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah Kertas',
                'sumber_sampah' => 'Dokumen, faktur, brosur, koran tamu',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah Plastik',
                'sumber_sampah' => 'Kemasan makanan, botol minuman, kantong belanja',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah Kaca',
                'sumber_sampah' => 'Botol minuman, gelas pecah, jendela',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah B3 Hotel',
                'sumber_sampah' => 'Lampu bekas, baterai, bahan kimia pembersih',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah Tekstil',
                'sumber_sampah' => 'Seragam staff, linen bekas, handuk rusak',
                'is_active' => true
            ],
            [
                'nama' => 'Sampah Elektronik',
                'sumber_sampah' => 'Remote TV rusak, peralatan elektronik bekas',
                'is_active' => true
            ]
        ];

        foreach ($jenisSampah as $data) {
            JenisSampah::create($data);
        }
    }
}
