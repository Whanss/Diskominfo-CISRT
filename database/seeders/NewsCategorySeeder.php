<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Keamanan Jaringan',
                'is_active' => true,
            ],
            [
                'name' => 'Ancaman Cyber',
                'is_active' => true,
            ],
            [
                'name' => 'Teknologi Keamanan',
                'is_active' => true,
            ],
            [
                'name' => 'Insiden Keamanan',
                'is_active' => true,
            ],
            [
                'name' => 'Regulasi & Kebijakan',
                'is_active' => true,
            ],
            [
                'name' => 'Pelatihan & Edukasi',
                'is_active' => true,
            ],
            [
                'name' => 'Berita Umum',
                'is_active' => true,
            ],
            [
                'name' => 'Tips & Best Practices',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            NewsCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('NewsCategory seeder completed successfully!');
    }
}
