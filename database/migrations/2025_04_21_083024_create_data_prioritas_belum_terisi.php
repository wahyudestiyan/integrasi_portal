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
        Schema::create('data_prioritas_belum_terisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_token_id')->constrained('instansi_tokens')->onDelete('cascade');
            $table->year('tahun');
            $table->string('judul_data');
            $table->string('keterangan')->nullable(); // misalnya "belum masuk di portal", "belum ada nilai tahun 2024"
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_prioritas_belum_terisi');
    }
};
