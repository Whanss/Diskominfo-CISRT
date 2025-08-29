<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Keep the status enum consistent with our standardized values
            $table->enum('status', ['pending', 'diterima/approved', 'selesai/completed', 'ditolak/rejected'])
                ->default('pending')
                ->change();

            // Add accepted_at column for tracking when tickets are accepted
            $table->timestamp('accepted_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Revert back to original status enum
            $table->enum('status', ['pending', 'diterima/approved', 'selesai/completed', 'ditolak/rejected'])
                ->default('pending')
                ->change();
        });
    }
};
