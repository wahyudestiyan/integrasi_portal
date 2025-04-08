<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_api_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('data_api_id')->nullable(); // untuk data baru mungkin belum ada
            $table->foreignId('instansi_token_id')->constrained()->onDelete('cascade');
            $table->string('tipe_perubahan', 30); // Sudah jadi string, bukan enum
            $table->string('judul_lama')->nullable();
            $table->string('judul_baru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_api_logs');
    }
};
