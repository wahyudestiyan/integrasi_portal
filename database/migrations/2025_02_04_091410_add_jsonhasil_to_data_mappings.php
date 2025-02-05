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
        Schema::table('data_mappings', function (Blueprint $table) {
            $table->longText('jsonhasil')->nullable(); // Menambahkan kolom jsonhasil
        });
    }
    
    public function down()
    {
        Schema::table('data_mappings', function (Blueprint $table) {
            $table->dropColumn('jsonhasil'); // Menghapus kolom jsonhasil jika rollback
        });
    }
};
