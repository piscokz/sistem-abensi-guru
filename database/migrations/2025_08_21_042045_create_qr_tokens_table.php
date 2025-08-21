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
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->onDelete('cascade');
            $table->foreignId('generated_by_siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('jadwal_detail_id')->constrained('jadwal_details')->onDelete('cascade');
            $table->string('token')->unique();
            $table->enum('status', ['aktif', 'telah_digunakan', 'expired'])->default('aktif');
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_tokens');
    }
};
