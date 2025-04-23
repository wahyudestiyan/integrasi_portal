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
        Schema::table('data_prioritas_sk_sekda', function (Blueprint $table) {
            $table->string('keterangan')->nullable()->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('data_prioritas_sk_sekda', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
