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
        Schema::table('api_responses', function (Blueprint $table) {
            $table->timestamp('version_timestamp')->nullable()->after('response_data'); // Waktu versi
            $table->boolean('is_latest')->default(true)->after('version_timestamp'); // Flag versi terbaru
        });
    }

    public function down()
    {
        Schema::table('api_responses', function (Blueprint $table) {
            $table->dropColumn(['version_timestamp', 'is_latest']);
        });
    }
};
