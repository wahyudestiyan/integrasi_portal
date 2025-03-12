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
        Schema::table('api_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('api_responses', 'apibps_id')) { 
                $table->unsignedBigInteger('apibps_id')->nullable()->after('api_id');
                $table->foreign('apibps_id')->references('id')->on('apibps')->onDelete('cascade');
            }

            $table->unsignedBigInteger('api_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_responses', function (Blueprint $table) {
            if (Schema::hasColumn('api_responses', 'apibps_id')) { 
                $table->dropForeign(['apibps_id']);
                $table->dropColumn('apibps_id');
            }

            $table->unsignedBigInteger('api_id')->nullable(false)->change();
        });
    }
};
