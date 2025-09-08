<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'layanan_category_id')) {
                $table->foreignId('layanan_category_id')->nullable()->after('layanan_id')->constrained('layanan_categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'layanan_category_id')) {
                $table->dropConstrainedForeignId('layanan_category_id');
            }
        });
    }
};