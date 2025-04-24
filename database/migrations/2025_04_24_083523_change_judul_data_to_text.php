<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Ubah kolom judul_data menjadi text di data_prioritas_sk_sekda
        Schema::table('data_prioritas_sk_sekda', function (Blueprint $table) {
            $table->text('judul_data')->change();
        });

        // Ubah kolom judul_data menjadi text di data_prioritas_belum_terisi
        Schema::table('data_prioritas_belum_terisi', function (Blueprint $table) {
            $table->text('judul_data')->change();
        });
    }

    public function down()
    {
        // Kembalikan kolom ke string (VARCHAR 255) jika rollback
        Schema::table('data_prioritas_sk_sekda', function (Blueprint $table) {
            $table->string('judul_data')->change();
        });

        Schema::table('data_prioritas_belum_terisi', function (Blueprint $table) {
            $table->string('judul_data')->change();
        });
    }
};
