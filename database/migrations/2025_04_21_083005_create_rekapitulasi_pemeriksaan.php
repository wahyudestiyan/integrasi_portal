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
        Schema::create('rekapitulasi_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_token_id')->constrained('instansi_tokens')->onDelete('cascade');
            $table->year('tahun');
            $table->integer('jumlah_sk_sekda')->default(0);
            $table->integer('jumlah_terdaftar_di_portal')->default(0);
            $table->integer('jumlah_data_terisi')->default(0);
            $table->enum('status', ['Lengkap', 'Belum Lengkap'])->default('Belum Lengkap');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekapitulasi_pemeriksaan');
    }
};
