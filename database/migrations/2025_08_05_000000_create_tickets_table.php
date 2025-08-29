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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('code_tracking')->unique();
            $table->string('nama_pelapor');
            $table->string('no_hp')->nullable();
            $table->string('email');
            $table->string('description');
            $table->enum('status', ['pending', 'diterima/approved', 'selesai/completed', 'ditolak/rejected'])
                ->default('pending');
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupaten')->onDelete('set null');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatan')->onDelete('set null');
            $table->timestamps(); // Sudah termasuk created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
