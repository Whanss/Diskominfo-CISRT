<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Check if user with email 'test@example.com' exists before creating
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Seed geographical data (kabupaten and kecamatan)
        $this->call(kabKecSeeder::class);

        // Seed news categories first
        $this->call(NewsCategorySeeder::class);

        // Seed services (layanan)
        $this->call(MasterLayananSeeder::class);

        // Seed news articles
        $this->call(NewsSeeder::class);

        $this->command->info('All seeders completed successfully!');
    }
}
