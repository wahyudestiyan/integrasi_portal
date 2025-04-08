<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_apis', function (Blueprint $table) {
            $table->id(); // Primary key lokal (auto-increment dari Laravel)
            $table->foreignId('instansi_token_id')->constrained()->onDelete('cascade');
            $table->string('id_api')->nullable();
            $table->string('judul');
            $table->string('tahun_data')->nullable(); // bisa menampung 2019,2020,2023, dst
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('data_apis');
    }
};
