<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndahKegiatan extends Model
{
    protected $table = 'indah_kegiatan';

    protected $primaryKey = 'id'; // id lokal autoincrement
    public $incrementing = true; // karena id lokal autoincrement
    protected $keyType = 'int';

    protected $fillable = [
        'id_keg', // ID dari API
        'id_kegiatan_mms',
        'judul_kegiatan',
        'tahun',
        'jenis_statistik',
        'cara_pengumpulan_data',
        'sektor_kegiatan',
        'identitas_rekomendasi',
        'i_instansi_penyelanggara',
        'i_alamat',
        'i_telepon',
        'i_email',
        'i_faksimile',
        'ii_unit_eselon1',
        'ii_unit_eselon2',
        'ii_pj_nama',
        'ii_pj_jabatan',
        'ii_pj_alamat',
        'ii_pj_telepon',
        'ii_pj_email',
        'ii_pj_faksimile',
        'iii_latar_belakang_kegiatan',
        'iii_tujuan_kegiatan',
        'iii_jadwal_perencanaan_kegiatan',
        'iii_jadwal_desain',
        'iii_jadwal_pengumpulan_data',
        'iii_jadwal_pengolahan_data',
        'iii_jadwal_analisis',
        'iii_jadwal_diseminasi_hasil',
        'iii_jadwal_evaluasi',
        'iii_variabel_yang_dikumpulkan',
        'iv_kegiatan_ini_dilakukan',
        'iv_frekuensi_penyelanggara',
        'iv_tipe_pengumpulan_data',
        'iv_cakupan_wilayah_pengumpulan_data',
        'iv_sebagian_cakupan_wilayah_pengumpulan_data',
        'iv_metode_pengumpulan_data',
        'iv_sarana_pengumpulan_data',
        'iv_unit_pengumpulan_data',
        'v_jenis_rancangan_sampel',
        'v_metode_pemilihan_sampel_tahap_terakhir',
        'v_metode_yang_digunakan',
        'v_kerangka_sampel_tahap_terakhir',
        'v_fraksi_sampel_keseluruhan',
        'v_nilai_perkiraan_sampling_error_variabel_utama',
        'v_unit_sampel',
        'v_unit_observasi',
        'vi_apakah_melakukan_uji_coba',
        'vi_metode_pemeriksaan_kualitas_pengumpulan_data',
        'vi_apakah_melakukan_penyesuaian_nonrespon',
        'vi_petugas_pengumpulan_data',
        'vi_persyaratan_pendidikan_terendah_petugas_pengumpulan_data',
        'vi_jumlah_petugas_supervisor',
        'vi_jumlah_petugas_enumerator',
        'vi_apakah_melakukan_pelatihan_petugas',
        'vii_tahapan_pengolahan_data',
        'vii_metode_analisis',
        'vii_unit_analisis',
        'vii_tingkat_penyajian_hasil_analisis',
        'viii_ketersediaan_produk_tercetak',
        'viii_ketersediaan_produk_digital',
        'viii_ketersediaan_produk_mikrodata',
        'viii_rencana_jadwal_rilis_produk_tercetak',
        'viii_rencana_jadwal_rilis_produk_digital',
        'viii_rencana_jadwal_rilis_produk_mikrodata',
        'requested_by',
        'updated_by',
        'approved_by',
        'produsen_data_name',
        'produsen_data_province_code',
        'produsen_data_city_code',
        'total_msvar',
        'total_msind',
        'last_sync',
        'status',
        'submission_period',
        'link_mskeg'
    ];

    protected $casts = [
        'iii_jadwal_perencanaan_kegiatan' => 'array',
        'iii_jadwal_desain' => 'array',
        'iii_jadwal_pengumpulan_data' => 'array',
        'iii_jadwal_pengolahan_data' => 'array',
        'iii_jadwal_analisis' => 'array',
        'iii_jadwal_diseminasi_hasil' => 'array',
        'iii_jadwal_evaluasi' => 'array',
        'iii_variabel_yang_dikumpulkan' => 'array',
        'iv_sebagian_cakupan_wilayah_pengumpulan_data' => 'array',
        'viii_rencana_jadwal_rilis_produk_tercetak' => 'array',
        'viii_rencana_jadwal_rilis_produk_digital' => 'array',
        'viii_rencana_jadwal_rilis_produk_mikrodata' => 'array',
        'last_sync' => 'datetime',
    ];

    public function variabels()
    {
        return $this->hasMany(Msvar::class, 'id_mskeg', 'id_keg');
    }

    public function indikator()
    {
        return $this->hasMany(Msind::class, 'id_mskeg', 'id_keg');
    }
}
