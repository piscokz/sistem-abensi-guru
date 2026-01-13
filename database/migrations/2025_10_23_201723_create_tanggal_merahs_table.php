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
        Schema::create('tanggal_merahs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique(); // contoh: 2025-10-20
            $table->string('keterangan')->nullable(); // contoh: Hari Raya, Libur Nasional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggal_merahs');
    }
};
