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
    Schema::create('indah_kegiatan', function (Blueprint $table) {
        // Primary key lokal auto-increment
        $table->bigIncrements('id'); // ID lokal

        // Tambahkan kolom untuk ID dari API
        $table->unsignedBigInteger('id_keg')->unique(); // ID dari API

        $table->unsignedBigInteger('id_kegiatan_mms')->nullable(); // opsional jika tidak selalu tersedia
        $table->string('judul_kegiatan');
        $table->string('tahun');
        $table->string('jenis_statistik')->nullable();
        $table->string('cara_pengumpulan_data')->nullable();
        $table->string('sektor_kegiatan')->nullable();
        $table->text('identitas_rekomendasi')->nullable();

        // Selanjutnya semua kolom tetap sama
        $table->string('i_instansi_penyelanggara')->nullable();
        $table->string('i_alamat')->nullable();
        $table->string('i_telepon')->nullable();
        $table->string('i_email')->nullable();
        $table->string('i_faksimile')->nullable();

        $table->string('ii_unit_eselon1')->nullable();
        $table->string('ii_unit_eselon2')->nullable();
        $table->string('ii_pj_nama')->nullable();
        $table->string('ii_pj_jabatan')->nullable();
        $table->string('ii_pj_alamat')->nullable();
        $table->string('ii_pj_telepon')->nullable();
        $table->string('ii_pj_email')->nullable();
        $table->string('ii_pj_faksimile')->nullable();

        $table->text('iii_latar_belakang_kegiatan')->nullable();
        $table->text('iii_tujuan_kegiatan')->nullable();
        $table->json('iii_jadwal_perencanaan_kegiatan')->nullable();
        $table->json('iii_jadwal_desain')->nullable();
        $table->json('iii_jadwal_pengumpulan_data')->nullable();
        $table->json('iii_jadwal_pengolahan_data')->nullable();
        $table->json('iii_jadwal_analisis')->nullable();
        $table->json('iii_jadwal_diseminasi_hasil')->nullable();
        $table->json('iii_jadwal_evaluasi')->nullable();
        $table->json('iii_variabel_yang_dikumpulkan')->nullable();

        $table->string('iv_kegiatan_ini_dilakukan')->nullable();
        $table->string('iv_frekuensi_penyelanggara')->nullable();
        $table->string('iv_tipe_pengumpulan_data')->nullable();
        $table->string('iv_cakupan_wilayah_pengumpulan_data')->nullable();
        $table->json('iv_sebagian_cakupan_wilayah_pengumpulan_data')->nullable();
        $table->string('iv_metode_pengumpulan_data')->nullable();
        $table->string('iv_sarana_pengumpulan_data')->nullable();
        $table->string('iv_unit_pengumpulan_data')->nullable();

        $table->string('v_jenis_rancangan_sampel')->nullable();
        $table->string('v_metode_pemilihan_sampel_tahap_terakhir')->nullable();
        $table->string('v_metode_yang_digunakan')->nullable();
        $table->string('v_kerangka_sampel_tahap_terakhir')->nullable();
        $table->string('v_fraksi_sampel_keseluruhan')->nullable();
        $table->string('v_nilai_perkiraan_sampling_error_variabel_utama')->nullable();
        $table->string('v_unit_sampel')->nullable();
        $table->string('v_unit_observasi')->nullable();

        $table->string('vi_apakah_melakukan_uji_coba')->nullable();
        $table->string('vi_metode_pemeriksaan_kualitas_pengumpulan_data')->nullable();
        $table->string('vi_apakah_melakukan_penyesuaian_nonrespon')->nullable();
        $table->string('vi_petugas_pengumpulan_data')->nullable();
        $table->string('vi_persyaratan_pendidikan_terendah_petugas_pengumpulan_data')->nullable();
        $table->integer('vi_jumlah_petugas_supervisor')->nullable();
        $table->integer('vi_jumlah_petugas_enumerator')->nullable();
        $table->string('vi_apakah_melakukan_pelatihan_petugas')->nullable();

        $table->string('vii_tahapan_pengolahan_data')->nullable();
        $table->string('vii_metode_analisis')->nullable();
        $table->string('vii_unit_analisis')->nullable();
        $table->string('vii_tingkat_penyajian_hasil_analisis')->nullable();

        $table->string('viii_ketersediaan_produk_tercetak')->nullable();
        $table->string('viii_ketersediaan_produk_digital')->nullable();
        $table->string('viii_ketersediaan_produk_mikrodata')->nullable();
        $table->json('viii_rencana_jadwal_rilis_produk_tercetak')->nullable();
        $table->json('viii_rencana_jadwal_rilis_produk_digital')->nullable();
        $table->json('viii_rencana_jadwal_rilis_produk_mikrodata')->nullable();

        $table->string('requested_by')->nullable();
        $table->string('updated_by')->nullable();
        $table->string('approved_by')->nullable();
        $table->string('produsen_data_name')->nullable();
        $table->string('produsen_data_province_code')->nullable();
        $table->string('produsen_data_city_code')->nullable();
        $table->integer('total_msvar')->nullable();
        $table->integer('total_msind')->nullable();
        $table->timestamp('last_sync')->nullable();
        $table->string('status')->nullable();
        $table->integer('submission_period')->nullable();
        $table->string('link_mskeg')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indah_kegiatan');
    }
};
