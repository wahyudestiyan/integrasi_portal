<?php

namespace App\Services;

use App\Models\IndahKegiatan;
use App\Models\Msvar;
use App\Models\Msind;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndahKegiatanService
{
    public static function fetchKegiatan($tahun = null)
    {
        $tahun = $tahun ?: date('Y');
        $token = config('services.indah_bps.token');
        $baseUrl = 'https://dna.web.bps.go.id/api/metadata/mskeg/search';

        $page = 1;
        $length = 100;

        do {
            $response = Http::withToken($token)->get($baseUrl, [
                'province' => '33',
                'city' => '00',
                'year' => $tahun,
                'staticticsType' => 'STATISTIK_SEKTORAL',
                'length' => $length,
                'page' => $page,
            ]);

            if (!$response->ok()) {
                Log::error("Gagal mengambil data kegiatan. Status: {$response->status()}");
                break;
            }

            $json = $response->json();

            if (!isset($json['result']['data']) || !is_array($json['result']['data'])) {
                Log::warning("Data kegiatan tidak ditemukan atau format tidak sesuai pada halaman {$page}");
                break;
            }

            $items = $json['result']['data'];

            foreach ($items as $item) {
                try {
                    // Simpan atau update berdasarkan id_keg (bukan id lokal)
                    $kegiatan = IndahKegiatan::updateOrCreate(
                        ['id_keg' => $item['id']],
                        [
                            'id_kegiatan_mms' => $item['id_kegiatan_mms'],
                            'judul_kegiatan' => $item['judul_kegiatan'],
                            'tahun' => $item['tahun'],
                            'jenis_statistik' => $item['jenis_statistik'] ?? null,
                            'cara_pengumpulan_data' => $item['cara_pengumpulan_data'] ?? null,
                            'sektor_kegiatan' => $item['sektor_kegiatan'] ?? null,
                            'identitas_rekomendasi' => $item['identitas_rekomendasi'] ?? null,

                            // Instansi Penyelenggara
                            'i_instansi_penyelanggara' => $item['i_instansi_penyelanggara'] ?? null,
                            'i_alamat' => $item['i_alamat'] ?? null,
                            'i_telepon' => $item['i_telepon'] ?? null,
                            'i_email' => $item['i_email'] ?? null,
                            'i_faksimile' => $item['i_faksimile'] ?? null,

                            // Penanggung Jawab
                            'ii_unit_eselon1' => $item['ii_unit_eselon1'] ?? null,
                            'ii_unit_eselon2' => $item['ii_unit_eselon2'] ?? null,
                            'ii_pj_nama' => $item['ii_pj_nama'] ?? null,
                            'ii_pj_jabatan' => $item['ii_pj_jabatan'] ?? null,
                            'ii_pj_alamat' => $item['ii_pj_alamat'] ?? null,
                            'ii_pj_telepon' => $item['ii_pj_telepon'] ?? null,
                            'ii_pj_email' => $item['ii_pj_email'] ?? null,
                            'ii_pj_faksimile' => $item['ii_pj_faksimile'] ?? null,

                            // Deskripsi
                            'iii_latar_belakang_kegiatan' => $item['iii_latar_belakang_kegiatan'] ?? null,
                            'iii_tujuan_kegiatan' => $item['iii_tujuan_kegiatan'] ?? null,
                            'iii_jadwal_perencanaan_kegiatan' => json_decode($item['iii_jadwal_perencanaan_kegiatan'] ?? '[]', true),
                            'iii_jadwal_desain' => json_decode($item['iii_jadwal_desain'] ?? '[]', true),
                            'iii_jadwal_pengumpulan_data' => json_decode($item['iii_jadwal_pengumpulan_data'] ?? '[]', true),
                            'iii_jadwal_pengolahan_data' => json_decode($item['iii_jadwal_pengolahan_data'] ?? '[]', true),
                            'iii_jadwal_analisis' => json_decode($item['iii_jadwal_analisis'] ?? '[]', true),
                            'iii_jadwal_diseminasi_hasil' => json_decode($item['iii_jadwal_diseminasi_hasil'] ?? '[]', true),
                            'iii_jadwal_evaluasi' => json_decode($item['iii_jadwal_evaluasi'] ?? '[]', true),
                            'iii_variabel_yang_dikumpulkan' => json_decode($item['iii_variabel_yang_dikumpulkan'] ?? '[]', true),

                            // Info Pengumpulan
                            'iv_kegiatan_ini_dilakukan' => $item['iv_kegiatan_ini_dilakukan'] ?? null,
                            'iv_frekuensi_penyelanggara' => $item['iv_frekuensi_penyelanggara'] ?? null,
                            'iv_tipe_pengumpulan_data' => $item['iv_tipe_pengumpulan_data'] ?? null,
                            'iv_cakupan_wilayah_pengumpulan_data' => $item['iv_cakupan_wilayah_pengumpulan_data'] ?? null,
                            'iv_sebagian_cakupan_wilayah_pengumpulan_data' => json_decode($item['iv_sebagian_cakupan_wilayah_pengumpulan_data'] ?? '[]', true),
                            'iv_metode_pengumpulan_data' => $item['iv_metode_pengumpulan_data'] ?? null,
                            'iv_sarana_pengumpulan_data' => $item['iv_sarana_pengumpulan_data'] ?? null,
                            'iv_unit_pengumpulan_data' => $item['iv_unit_pengumpulan_data'] ?? null,

                            // Sampel & Uji Coba
                            'v_jenis_rancangan_sampel' => $item['v_jenis_rancangan_sampel'] ?? null,
                            'v_metode_pemilihan_sampel_tahap_terakhir' => $item['v_metode_pemilihan_sampel_tahap_terakhir'] ?? null,
                            'v_metode_yang_digunakan' => $item['v_metode_yang_digunakan'] ?? null,
                            'v_kerangka_sampel_tahap_terakhir' => $item['v_kerangka_sampel_tahap_terakhir'] ?? null,
                            'v_fraksi_sampel_keseluruhan' => $item['v_fraksi_sampel_keseluruhan'] ?? null,
                            'v_nilai_perkiraan_sampling_error_variabel_utama' => $item['v_nilai_perkiraan_sampling_error_variabel_utama'] ?? null,
                            'v_unit_sampel' => $item['v_unit_sampel'] ?? null,
                            'v_unit_observasi' => $item['v_unit_observasi'] ?? null,
                            'vi_apakah_melakukan_uji_coba' => $item['vi_apakah_melakukan_uji_coba'] ?? null,
                            'vi_metode_pemeriksaan_kualitas_pengumpulan_data' => $item['vi_metode_pemeriksaan_kualitas_pengumpulan_data'] ?? null,
                            'vi_apakah_melakukan_penyesuaian_nonrespon' => $item['vi_apakah_melakukan_penyesuaian_nonrespon'] ?? null,
                            'vi_petugas_pengumpulan_data' => $item['vi_petugas_pengumpulan_data'] ?? null,
                            'vi_persyaratan_pendidikan_terendah_petugas_pengumpulan_data' => $item['vi_persyaratan_pendidikan_terendah_petugas_pengumpulan_data'] ?? null,
                            'vi_jumlah_petugas_supervisor' => $item['vi_jumlah_petugas_supervisor'] ?? null,
                            'vi_jumlah_petugas_enumerator' => $item['vi_jumlah_petugas_enumerator'] ?? null,
                            'vi_apakah_melakukan_pelatihan_petugas' => $item['vi_apakah_melakukan_pelatihan_petugas'] ?? null,

                            // Analisis & Produk
                            'vii_tahapan_pengolahan_data' => $item['vii_tahapan_pengolahan_data'] ?? null,
                            'vii_metode_analisis' => $item['vii_metode_analisis'] ?? null,
                            'vii_unit_analisis' => $item['vii_unit_analisis'] ?? null,
                            'vii_tingkat_penyajian_hasil_analisis' => $item['vii_tingkat_penyajian_hasil_analisis'] ?? null,
                            'viii_ketersediaan_produk_tercetak' => $item['viii_ketersediaan_produk_tercetak'] ?? null,
                            'viii_ketersediaan_produk_digital' => $item['viii_ketersediaan_produk_digital'] ?? null,
                            'viii_ketersediaan_produk_mikrodata' => $item['viii_ketersediaan_produk_mikrodata'] ?? null,
                            'viii_rencana_jadwal_rilis_produk_tercetak' => json_decode($item['viii_rencana_jadwal_rilis_produk_tercetak'] ?? '[]', true),
                            'viii_rencana_jadwal_rilis_produk_digital' => json_decode($item['viii_rencana_jadwal_rilis_produk_digital'] ?? '[]', true),
                            'viii_rencana_jadwal_rilis_produk_mikrodata' => json_decode($item['viii_rencana_jadwal_rilis_produk_mikrodata'] ?? '[]', true),

                            // Metadata
                            'requested_by' => $item['requested_by'] ?? null,
                            'updated_by' => $item['updated_by'] ?? null,
                            'approved_by' => $item['approved_by'] ?? null,
                            'produsen_data_name' => $item['produsen_data_name'] ?? null,
                            'produsen_data_province_code' => $item['produsen_data_province_code'] ?? null,
                            'produsen_data_city_code' => $item['produsen_data_city_code'] ?? null,
                            'total_msvar' => $item['total_msvar'] ?? null,
                            'total_msind' => $item['total_msind'] ?? null,
                            'last_sync' => $item['last_sync'] ?? null,
                            'status' => $item['status'] ?? null,
                            'submission_period' => $item['submission_period'] ?? null,
                            'link_mskeg' => $item['link_mskeg'] ?? null,

                            'created_at' => $item['created_at'] ?? now(),
                           'updated_at' => $item['updated_at'] ?? now(),


                        ]
                    );

                    // self::fetchAndStoreMsvar($item['id'], $item['id']);
                    // self::fetchAndStoreMsind($item['id'], $item['id']);

                } catch (\Throwable $e) {
                    Log::error("Gagal sinkron kegiatan ID {$item['id']}: " . $e->getMessage());
                }
            }

            $page++;
        } while (!is_null($json['result']['next_page_url']));

        return true;
    }

    public static function fetchAndStoreMsvar($idApi, $kegiatanId)
    {
        $token = config('services.indah_bps.token');
        $url = "https://dna.web.bps.go.id/api/metadata/msvar/under/{$idApi}";

        $response = Http::withToken($token)->get($url);
        if (!$response->ok()) {
            Log::error("Gagal mengambil data msvar untuk kegiatan {$idApi}, status: {$response->status()}");
            return;
        }

         $data = $response->json()['result'] ?? [];
Log::info("Msvar data dari API id={$idApi}", $data);
        foreach ($data as $var) {
            try {
                Msvar::updateOrCreate(
                    ['id_variabel_mms' => $var['id_variabel_mms']],
                    [
                        'id_api' => $var['id'] ?? null,
                        'id_mskeg' => $kegiatanId,
                        'nama' => $var['nama'] ?? '',
                        'alias' => $var['alias'] ?? null,
                        'konsep' => $var['konsep'] ?? null,
                        'definisi' => $var['definisi'] ?? null,
                        'referensi_pemilihan' => $var['referensi_pemilihan'] ?? null,
                        'referensi_waktu' => $var['referensi_waktu'] ?? '',
                        'ukuran' => $var['ukuran'] ?? '',
                        'satuan' => $var['satuan'] ?? '',
                        'tipe_data' => $var['tipe_data'] ?? '',
                        'value_domain' => $var['value_domain'] ?? null,
                        'aturan_validasi' => $var['aturan_validasi'] ?? null,
                        'kalimat_pertanyaan' => $var['kalimat_pertanyaan'] ?? null,
                        'apakah_variabel_bisa_diakses_umum' => $var['apakah_variabel_bisa_diakses_umum'] ?? null,
                        'requested_by' => $var['requested_by'] ?? null,
                        'updated_by' => $var['updated_by'] ?? null,
                        'approved_by' => $var['approved_by'] ?? null,
                        'produsen_data_name' => $var['produsen_data_name'] ?? null,
                        'produsen_data_province_code' => $var['produsen_data_province_code'] ?? null,
                        'produsen_data_city_code' => $var['produsen_data_city_code'] ?? null,
                        'last_sync' => $var['last_sync'] ?? null,
                        'status' => $var['status'] ?? null,
                        'submission_period' => $var['submission_period'] ?? null,
                        'link_msvar' => $var['link_msvar'] ?? null,
                        'link_mskeg' => $var['link_mskeg'] ?? null,

                        'created_at' => $var['created_at'] ?? now(),
                         'updated_at' => $var['updated_at'] ?? now(),

                    ]
                );
            } catch (\Throwable $e) {
                Log::error("Gagal simpan msvar ID {$var['id_variabel_mms']}: " . $e->getMessage());
            }
        }
    }

   public static function fetchAndStoreMsind($idApi, $kegiatanId)
    {
        $token = config('services.indah_bps.token');
        $url = "https://dna.web.bps.go.id/api/metadata/msind/under/{$idApi}";

        $response = Http::withToken($token)->get($url);
        if (!$response->ok()) {
            Log::error("Gagal mengambil data msind untuk kegiatan {$idApi}, status: {$response->status()}");
            return;
        }

         $data = $response->json()['result'] ?? [];
Log::info("Msind data dari API id={$idApi}", $data);
        foreach ($data as $ind) {
            try {
                Msind::updateOrCreate(
                    ['id_indikator_mms' => $ind['id_indikator_mms']],
                    [
                        'id_api' => $ind['id'] ?? null,
                        'id_mskeg' => $kegiatanId,
                        'nama' => $ind['nama'] ?? '',
                        'konsep' => $ind['konsep'] ?? null,
                        'definisi' => $ind['definisi'] ?? null,
                        'interpretasi' => $ind['interpretasi'] ?? null,
                        'metode_perhitungan' => $ind['metode_perhitungan'] ?? null,
                        'rumus' => $ind['rumus'] ?? null,
                        'ukuran' => $ind['ukuran'] ?? null,
                        'satuan' => $ind['satuan'] ?? null,
                        'variabel_disaggregasi' => $ind['variabel_disaggregasi'] ?? null,
                        'apakah_indikator_komposit' => $ind['apakah_indikator_komposit'] ?? null,
                        'indikator_pembangun' => $ind['indikator_pembangun'] ?? null,
                        'variabel_pembangun' => $ind['variabel_pembangun'] ?? null,
                        'level_estimasi' => $ind['level_estimasi'] ?? null,
                        'apakah_indikator_bisa_diakses_umum' => $ind['apakah_indikator_bisa_diakses_umum'] ?? null,
                        'requested_by' => $ind['requested_by'] ?? null,
                        'updated_by' => $ind['updated_by'] ?? null,
                        'approved_by' => $ind['approved_by'] ?? null,
                        'produsen_data_name' => $ind['produsen_data_name'] ?? null,
                        'produsen_data_province_code' => $ind['produsen_data_province_code'] ?? null,
                        'produsen_data_city_code' => $ind['produsen_data_city_code'] ?? null,
                        'last_sync' => $ind['last_sync'] ?? null,
                        'status' => $ind['status'] ?? null,
                        'submission_period' => $ind['submission_period'] ?? null,
                        'link_msind' => $ind['link_msind'] ?? null,
                        'link_mskeg' => $ind['link_mskeg'] ?? null,

                        'created_at' => $ind['created_at'] ?? now(),
                     'updated_at' => $ind['updated_at'] ?? now(),
                    ]
                );
            } catch (\Throwable $e) {
                Log::error("Gagal simpan msind ID {$ind['id_indikator_mms']}: " . $e->getMessage());
            }
        }
    }
}
