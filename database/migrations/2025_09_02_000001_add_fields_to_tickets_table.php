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
            if (!Schema::hasColumn('tickets', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('kecamatan_id');
            }
            if (!Schema::hasColumn('tickets', 'layanan_type')) {
                $table->string('layanan_type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('tickets', 'layanan_custom')) {
                $table->string('layanan_custom')->nullable()->after('layanan_type');
            }
            if (!Schema::hasColumn('tickets', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('tickets', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable()->after('resolved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'attachment_path')) {
                $table->dropColumn('attachment_path');
            }
            if (Schema::hasColumn('tickets', 'layanan_type')) {
                $table->dropColumn('layanan_type');
            }
            if (Schema::hasColumn('tickets', 'layanan_custom')) {
                $table->dropColumn('layanan_custom');
            }
            if (Schema::hasColumn('tickets', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('tickets', 'resolution_notes')) {
                $table->dropColumn('resolution_notes');
            }
        });
    }
};
