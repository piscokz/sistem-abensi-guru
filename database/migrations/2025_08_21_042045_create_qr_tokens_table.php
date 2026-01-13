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
        // Schema::create('qr_tokens', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('token')->unique();
        //     $table->foreignId('jadwal_detail_id')->constrained('jadwal_details')->onDelete('cascade');
        //     $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        //     $table->enum('status', ['aktif', 'telah_digunakan', 'expired']);
        //     $table->timestamp('expires_at');
        //     $table->timestamps();
        // });

        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('jadwal_detail_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('used')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
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
