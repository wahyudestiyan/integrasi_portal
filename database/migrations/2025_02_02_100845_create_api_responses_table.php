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
        Schema::create('api_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id'); // Referensi ke tabel API
            $table->json('response_data'); // Simpan response dalam format JSON
            $table->timestamps();
    
            $table->foreign('api_id')->references('id')->on('apis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_responses');
    }
};
