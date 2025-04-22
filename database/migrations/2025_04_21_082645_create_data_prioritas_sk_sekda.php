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
        Schema::create('data_prioritas_sk_sekda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_token_id')->constrained('instansi_tokens')->onDelete('cascade');
            $table->string('judul_data');
            $table->string('id_data_portal')->nullable(); // Jika nanti ingin dikaitkan dengan ID dari API
            $table->year('tahun');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_prioritas_sk_sekda');
    }
};
