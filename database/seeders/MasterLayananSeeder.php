<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLayanan;

class MasterLayananSeeder extends Seeder
{
    public function run()
    {
        $layananData = [
            [
                'name' => 'Layanan Keamanan Aplikasi',
                'description' => 'Layanan untuk mengamankan aplikasi web dan mobile dari berbagai kerentanan',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Response Insiden',
                'description' => 'Layanan penanganan insiden keamanan cyber secara profesional',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Konsultasi Keamanan',
                'description' => 'Layanan konsultasi dan advisori keamanan informasi',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Audit Keamanan',
                'description' => 'Layanan audit dan penilaian tingkat keamanan sistem',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Pelatihan Keamanan',
                'description' => 'Layanan pelatihan kesadaran keamanan siber untuk pegawai',
                'is_active' => true,
            ],
        ];

        foreach ($layananData as $layanan) {
            MasterLayanan::firstOrCreate(
                ['name' => $layanan['name']],
                $layanan
            );
        }

        $this->command->info('MasterLayanan seeder completed successfully!');
    }
}
