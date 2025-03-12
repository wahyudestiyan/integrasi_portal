<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('instansi_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->string('bearer_token');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instansi_tokens');
    }
};

