<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class Kecamatanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'Batukliang',
            'Batukliang Utara',
            'Janapria',
            'Jonggat',
            'Kopang',
            'Praya',
            'Praya Barat',
            'Praya Barat Daya',
            'Praya Tengah',
            'Praya Timur',
            'Pringgarata',
            'Pujut'
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::create([
                'nama' => $kecamatan
            ]);
        }
    }
}
