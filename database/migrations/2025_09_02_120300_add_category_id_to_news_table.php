<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('category')->constrained('news_categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }
        });
    }
};
