<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

class kabKecSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Kabupaten Lombok Barat',
                'kecamatans' => [
                    'Batu Layar', 'Gerung', 'Gunungsari', 'Kediri', 'Kuripan', 'Labuapi', 'Lembar', 'Lingsar', 'Narmada', 'Sekotong'
                ],
            ],
            [
                'nama' => 'Kabupaten Lombok Tengah',
                'kecamatans' => [
                    'Batukliang', 'Batukliang Utara', 'Janapria', 'Jonggat', 'Kopang', 'Praya', 'Praya Barat', 'Praya Barat Daya', 'Praya Tengah', 'Praya Timur', 'Pringgarata', 'Pujut'
                ],
            ],
            [
                'nama' => 'Kabupaten Lombok Timur',
                'kecamatans' => [
                    'Keruak', 'Jerowaru', 'Sakra', 'Sakra Barat', 'Sakra Timur', 'Terara', 'Montong Gading', 'Sikur', 'Masbagik', 'Pringgasela', 'Sukamulia', 'Suralaga', 'Selong', 'Labuhan Haji', 'Pringgabaya', 'Suela', 'Aikmel', 'Wanasaba', 'Sembalun', 'Sambelia', 'Lenek'
                ],
            ],
            [
                'nama' => 'Kabupaten Lombok Utara',
                'kecamatans' => [
                    'Pemenang', 'Tanjung', 'Gangga', 'Kayangan', 'Bayan'
                ],
            ],
            [
                'nama' => 'Kota Mataram',
                'kecamatans' => [
                    'Ampenan', 'Cakranegara', 'Mataram', 'Sandubaya', 'Sekarbela', 'Selaparang'
                ],
            ],
        ];

        foreach ($data as $kabupatenData) {
            $kabupaten = Kabupaten::firstOrCreate(['nama' => $kabupatenData['nama']]);
            foreach ($kabupatenData['kecamatans'] as $kecamatanName) {
                Kecamatan::firstOrCreate([
                    'nama' => $kecamatanName,
                    'kabupaten_id' => $kabupaten->id,
                ]);
            }
        }
    }
}
