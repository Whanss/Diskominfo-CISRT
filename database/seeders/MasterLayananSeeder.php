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
                'name' => 'Layanan Keamanan Jaringan',
                'description' => 'Layanan untuk mengamankan infrastruktur jaringan dan mencegah serangan cyber',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Aplikasi',
                'description' => 'Layanan untuk mengamankan aplikasi web dan mobile dari berbagai kerentanan',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Data',
                'description' => 'Layanan untuk melindungi data sensitif dan informasi penting',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Monitoring Keamanan',
                'description' => 'Layanan pemantauan 24/7 terhadap aktivitas keamanan sistem',
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
            [
                'name' => 'Layanan Backup & Recovery',
                'description' => 'Layanan backup data dan recovery sistem dalam keadaan darurat',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Cloud',
                'description' => 'Layanan keamanan untuk infrastruktur cloud dan layanan berbasis cloud',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan IoT',
                'description' => 'Layanan keamanan untuk perangkat Internet of Things (IoT)',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Forensik Digital',
                'description' => 'Layanan investigasi forensik untuk insiden keamanan digital',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Email',
                'description' => 'Layanan keamanan untuk sistem email dan komunikasi elektronik',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Endpoint',
                'description' => 'Layanan keamanan untuk perangkat endpoint (komputer, laptop, mobile)',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan Keamanan Database',
                'description' => 'Layanan keamanan untuk sistem database dan penyimpanan data',
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
