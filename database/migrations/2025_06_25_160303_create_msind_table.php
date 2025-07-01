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
        Schema::create('msind', function (Blueprint $table) {
    $table->id();

    // ID dari API
    $table->unsignedBigInteger('id_indikator_mms')->unique(); // ID asli indikator dari API
    $table->unsignedBigInteger('id_api'); // biasanya field `id` dari API

    // Foreign key ke indah_kegiatan.id_keg
    $table->unsignedBigInteger('id_mskeg');

    $table->string('nama');
    $table->json('konsep')->nullable();
    $table->text('definisi')->nullable();
    $table->text('interpretasi')->nullable();
    $table->string('metode_perhitungan')->nullable();
    $table->string('rumus')->nullable();
    $table->string('ukuran')->nullable();
    $table->string('satuan')->nullable();

    $table->json('variabel_disaggregasi')->nullable();
    $table->string('apakah_indikator_komposit')->nullable();
    $table->json('indikator_pembangun')->nullable();
    $table->json('variabel_pembangun')->nullable();
    $table->string('level_estimasi')->nullable();
    $table->string('apakah_indikator_bisa_diakses_umum')->nullable();

    // Metadata umum
    $table->string('requested_by')->nullable();
    $table->string('updated_by')->nullable();
    $table->string('approved_by')->nullable();
    $table->string('produsen_data_name')->nullable();
    $table->string('produsen_data_province_code')->nullable();
    $table->string('produsen_data_city_code')->nullable();

    $table->timestamp('last_sync')->nullable();
    $table->string('status')->nullable();
    $table->integer('submission_period')->nullable();

    $table->string('link_msind')->nullable();
    $table->string('link_mskeg')->nullable();

    $table->timestamps(); // created_at & updated_at

    // ✅ Relasi ke kolom id_keg (bukan id lokal)
    $table->foreign('id_mskeg')
          ->references('id_keg')
          ->on('indah_kegiatan')
          ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msind');
    }
};
