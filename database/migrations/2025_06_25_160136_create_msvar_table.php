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
            Schema::create('msvar', function (Blueprint $table) {
    $table->id();

    // ID dari API
    $table->unsignedBigInteger('id_variabel_mms')->unique(); // ID variabel dari API
    $table->unsignedBigInteger('id_api'); // ID alias dari API

    // Foreign key ke indah_kegiatan.id_keg (bukan id lokal)
    $table->unsignedBigInteger('id_mskeg');

    $table->string('nama');
    $table->string('alias')->nullable();
    $table->json('konsep')->nullable();
    $table->text('definisi')->nullable();
    $table->json('referensi_pemilihan')->nullable();
    $table->string('referensi_waktu')->nullable();
    $table->string('ukuran')->nullable();
    $table->string('satuan')->nullable();
    $table->string('tipe_data')->nullable();
    $table->json('value_domain')->nullable();
    $table->json('aturan_validasi')->nullable();
    $table->text('kalimat_pertanyaan')->nullable();
    $table->string('apakah_variabel_bisa_diakses_umum')->nullable();

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

    $table->string('link_msvar')->nullable();
    $table->string('link_mskeg')->nullable();

    $table->timestamps();

    // 🔗 Relasi ke id_keg (ID API) di indah_kegiatan
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
        Schema::dropIfExists('msvar');
    }
};
