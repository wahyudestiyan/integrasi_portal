<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('data_mappings', function (Blueprint $table) {
            // Tambahkan kolom apibps_id jika belum ada
            if (!Schema::hasColumn('data_mappings', 'apibps_id')) { 
                $table->unsignedBigInteger('apibps_id')->nullable()->after('api_id');
                $table->foreign('apibps_id')->references('id')->on('apibps')->onDelete('cascade');
            }

            // Ubah api_id agar bisa null
            $table->unsignedBigInteger('api_id')->nullable()->change();

            // Tambahkan kolom source_field jika belum ada
            if (!Schema::hasColumn('data_mappings', 'source_field')) { 
                $table->string('source_field')->nullable()->after('apibps_id');
            }

            // Tambahkan kolom target_field jika belum ada
            if (!Schema::hasColumn('data_mappings', 'target_field')) { 
                $table->string('target_field')->nullable()->after('source_field');
            }

            // Tambahkan kolom jsonhasil jika belum ada
            if (!Schema::hasColumn('data_mappings', 'jsonhasil')) { 
                $table->json('jsonhasil')->nullable()->after('target_field');
            }
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('data_mappings', function (Blueprint $table) {
            // Hapus kolom apibps_id jika ada
            if (Schema::hasColumn('data_mappings', 'apibps_id')) { 
                $table->dropForeign(['apibps_id']);
                $table->dropColumn('apibps_id');
            }

            // Ubah api_id kembali menjadi tidak nullable
            $table->unsignedBigInteger('api_id')->nullable(false)->change();

            // Hapus kolom source_field jika ada
            if (Schema::hasColumn('data_mappings', 'source_field')) { 
                $table->dropColumn('source_field');
            }

            // Hapus kolom target_field jika ada
            if (Schema::hasColumn('data_mappings', 'target_field')) { 
                $table->dropColumn('target_field');
            }

            // Hapus kolom jsonhasil jika ada
            if (Schema::hasColumn('data_mappings', 'jsonhasil')) { 
                $table->dropColumn('jsonhasil');
            }
        });
    }
};
