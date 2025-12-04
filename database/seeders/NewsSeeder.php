<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $categories = NewsCategory::all();

        if ($categories->isEmpty()) {
            $this->command->error('No news categories found. Please seed news categories first.');
            return;
        }

        // Create 50 news items with random categories
        for ($i = 1; $i <= 12; $i++) {
            $title = "Sample News Title {$i}";
            $content = "This is the content for sample news item number {$i}. It contains some example text to simulate a real news article about cybersecurity, threats, and security best practices.";
            $excerpt = Str::limit($content, 100);
            $category = $categories->random();

            News::create([
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'category_id' => $category->id,
                'is_published' => true,
            ]);
        }

        $this->command->info('News seeder completed successfully!');
    }
}
