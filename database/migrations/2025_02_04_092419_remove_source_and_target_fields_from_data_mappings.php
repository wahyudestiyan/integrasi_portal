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
        // Menghapus kolom source_field dan target_field
        $table->dropColumn(['source_field', 'target_field']);
    });
}

public function down()
{
    Schema::table('data_mappings', function (Blueprint $table) {
        // Menambahkan kembali kolom jika migrasi dibatalkan
        $table->string('source_field');
        $table->string('target_field');
    });
}
};
