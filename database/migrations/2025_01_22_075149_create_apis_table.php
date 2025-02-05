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
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->string('nama_data');
            $table->string('url_api');
            $table->string('credential_key')->nullable();
            $table->string('id_data')->nullable();
            $table->enum('method', ['GET', 'POST']);
            $table->enum('status', ['Belum Terkirim', 'Terkirim'])->default('Belum Terkirim');   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
