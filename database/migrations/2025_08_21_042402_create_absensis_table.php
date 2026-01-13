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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('jadwal_detail_id')->constrained('jadwal_details')->onDelete('cascade');
            $table->dateTime('waktu_absen')->nullable();
            $table->foreignId('qr_token_id')->nullable()->constrained('qr_tokens');
            $table->enum('status', ['hadir', 'tidak_hadir'])->default('hadir');
            $table->enum('via', ['qr', 'piket', 'otomatis'])->default('qr');
            $table->timestamps();

            // pastikan satu kombinasi guru+jadwal_detail+tanggal unik
            $table->unique(['guru_id', 'jadwal_detail_id', 'waktu_absen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
