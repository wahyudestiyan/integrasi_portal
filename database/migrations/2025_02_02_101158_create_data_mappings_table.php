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
        Schema::create('data_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id'); // Referensi API asal
            $table->string('source_field'); // Field dari API asal
            $table->string('target_field'); // Field untuk API tujuan
            $table->timestamps();
    
            $table->foreign('api_id')->references('id')->on('apis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_mappings');
    }
};
