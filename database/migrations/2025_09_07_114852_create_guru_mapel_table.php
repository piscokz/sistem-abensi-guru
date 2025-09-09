<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_guru_mapel_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini menghubungkan guru dengan mapel yang mereka ajar.
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->primary(['guru_id', 'mapel_id']); // Composite primary key
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};