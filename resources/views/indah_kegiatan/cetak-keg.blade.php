<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Metadata Kegiatan</title>
    <style>
        /* PDF Specific CSS */
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif; /* Use common fonts */
            margin: 0;
            padding: 20px; /* Overall padding for the document */
            font-size: 10pt; /* Adjust base font size for PDF */
        }

        /* Ensure containers take full available width within margins */
        .full-width-box {
            width: 100%;
            padding: 0; /* Adjust padding if needed, but overall body padding should handle it */
            box-sizing: border-box;
            background-color: white;
        }

        
        /* Heading */
        h1 {
            font-weight: bold;
            font-size: 20pt; /* Smaller font size for PDF */
            line-height: 1.2;
            margin: 15px 0; /* Adjust margins */
            text-align: center;
        }

        /* Label structure */
        .judul label,
        .isitabel label,
        .top label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .isi label,
        .isian label,
        .dobel label {
            font-weight: normal;
            display: block;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .isi label {
            border-bottom: 1px solid #eee; /* Lighter border for subtlety */
            text-align: justify;
            padding-bottom: 5px;
        }

        /* Spacing between sections/details */
        .detail {
            margin-bottom: 10px; /* Reduce spacing slightly */
        }

        /* Table headers */
        th.bg-header, th.bg-judul {
            background-color: #dcdcdc; /* Slightly darker grey for headers */
            height: 25px; /* Reduce height */
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            padding: 5px;
        }

        /* Table content */
        table.b, .table-bordered { /* Apply to all relevant tables */
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 15px; /* Space after tables */
        }

       /* Table content */
table.b, .table-bordered {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 15px;
    border: 1px solid #000; /* Tambahkan juga border default */
}

th.t, td.d, td.e, .table-bordered th, .table-bordered td {
    border: 0.3pt solid #000; /* Tipis dan tetap hitam */
    padding: 6px;
    font-size: 9pt;
    vertical-align: top;
}



        th.t, td.e, .table-bordered th {
            text-align: center;
        }

        td.d, .table-bordered td {
            text-align: left;
        }

        /* Specific alignment classes */
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .text-muted { color: #888; } /* For empty messages */

        /* Buttons (will likely be ignored or rendered as text, remove if not needed) */
        .btn-active, .btn-inactive, .no-border-button {
            display: none; /* Hide buttons in PDF */
        }
        
        /* Page break for new major sections */
        .page-break {
            page-break-after: always;
        }
        thead.table-light {
    background-color:rgb(221, 221, 221) !important; /* abu-abu agak gelap */
}
    </style>
</head>
<body>

    <h1 class="text-center">METADATA STATISTIK</h1>
    <h1 class="text-center" style="margin-top: 0;">KEGIATAN</h1>

    <div class="full-width-box">
        <div class="top"><label>Judul Kegiatan:</label></div>
        <div class="isi">
            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->judul_kegiatan))) }}</label>
        </div>

        <div class="top"><label>Tahun:</label></div>
        <div class="isi">
            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->tahun))) }}</label>
        </div>

        <div class="top"><label>Cara Pengumpulan Data:</label></div>
        <div class="isi">
            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->cara_pengumpulan_data))) }}</label>
        </div>

        <div class="top"><label>Sektor Kegiatan:</label></div>
        <div class="isi">
            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->sektor_kegiatan))) }}</label>
        </div>

        <br class="page-break"> <table width="100%">
            <tr>
                <th height="30px" class="bg-header" colspan="2">I. PENYELENGGARA</th>
            </tr>
            <tr><td style="padding: 5px;"></td></tr> <tr>
                <td colspan="2">
                    <div class="top"><label>1.1. Instansi Penyelenggara:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_instansi_penyelanggara))) }}</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>1.2. Alamat Lengkap Instansi Penyelenggara:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_alamat))) }}</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="detail">
                        <table width="100%">
                            <tr>
                                <td style="width: 33%;">
                                    <div class="top"><label>Telepon:</label></div>
                                    <div class="isi">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_telepon))) }}</label>
                                    </div>
                                </td>
                                <td style="width: 33%;">
                                    <div class="top"><label>Faksimile:</label></div>
                                    <div class="isi">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_faksimile))) }}</label>
                                    </div>
                                </td>
                                <td style="width: 34%;">
                                    <div class="top"><label>Email:</label></div>
                                    <div class="isi">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_email))) }}</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header" colspan="2">II. PENANGGUNG JAWAB</th>
            </tr>
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>2.1. Unit Eselon Penanggung Jawab:</label></div>
                    <div class="detail">
                        <table width="100%">
                            <tr>
                                <td style="width: 50%;">
                                    <div class="top"><label>Eselon 1:</label></div>
                                    <div class="isi">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_unit_eselon1))) }}</label>
                                    </div>
                                </td>
                                <td style="width: 50%;">
                                    <div class="top"><label>Eselon 2:</label></div>
                                    <div class="isi">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_unit_eselon2))) }}</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>2.2. Penanggung Jawab Teknis (setingkat Eselon 3):</label></div>
                    <div class="detail">
                        <table width="100%">
                            <tr>
                                <td colspan="3"> <div class="top"><label>Nama:</label></div>
                                    <div class="isian">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_nama))) }}</label>
                                    </div>
                                    <div class="top"><label>Jabatan:</label></div>
                                    <div class="isian">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_jabatan))) }}</label>
                                    </div>
                                    <div class="top"><label>Alamat:</label></div>
                                    <div class="isian">
                                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_alamat))) }}</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="detail">
                            <table width="100%">
                                <tr>
                                    <td style="width: 33%;">
                                        <div class="top"><label>Telepon:</label></div>
                                        <div class="isi">
                                            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_telepon))) }}</label>
                                        </div>
                                    </td>
                                    <td style="width: 33%;">
                                        <div class="top"><label>Faksimile:</label></div>
                                        <div class="isi">
                                            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_faksimile))) }}</label>
                                        </div>
                                    </td>
                                    <td style="width: 34%;">
                                        <div class="top"><label>Email:</label></div>
                                        <div class="isi">
                                            <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_email))) }}</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <br class="page-break">

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header">III. PERENCANAAN DAN PERSIAPAN</th>
            </tr>
        </table>

        <table width="100%">
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>3.1. Latar Belakang Kegiatan:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iii_latar_belakang_kegiatan))) }}</label>
                    </div>

                    <div class="top"><label>3.2. Tujuan Kegiatan:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iii_tujuan_kegiatan))) }}</label>
                    </div>

                    @php
                    function ambilJadwal($data) {
                        if (is_string($data)) {
                            $array = json_decode($data, true);
                        } else {
                            $array = $data;
                        }
                        return $array[0] ?? [];
                    }

                    $perencanaan = ambilJadwal($kegiatan->iii_jadwal_perencanaan_kegiatan);
                    $desain = ambilJadwal($kegiatan->iii_jadwal_desain);
                    $pengumpulan = ambilJadwal($kegiatan->iii_jadwal_pengumpulan_data);
                    $pengolahan = ambilJadwal($kegiatan->iii_jadwal_pengolahan_data);
                    $analisis = ambilJadwal($kegiatan->iii_jadwal_analisis);
                    $diseminasi = ambilJadwal($kegiatan->iii_jadwal_diseminasi_hasil);
                    $evaluasi = ambilJadwal($kegiatan->iii_jadwal_evaluasi);

                    function formatTanggal($tanggal) {
                        return $tanggal ? \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') : '-';
                    }
                    @endphp

                    <div class="mb-3">
                        <label class="top" style="font-weight: bold;">3.3. Rencana Jadwal Kegiatan:</label>
                           <br></br>
                    </div>
                 

                    <table class="table-bordered" style="margin-bottom: 15px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%"></th>
                                <th style="width: 45%"></th>
                                <th class="text-center" style="width: 25%;">Tanggal Mulai</th>
                                <th class="text-center" style="width: 25%;">Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">A.</td>
                                <td class="fw-bold" colspan="3">Perencanaan</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>1. Perencanaan Kegiatan</td>
                                <td class="text-center">{{ formatTanggal($perencanaan['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($perencanaan['akhir'] ?? null) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>2. Desain</td>
                                <td class="text-center">{{ formatTanggal($desain['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($desain['akhir'] ?? null) }}</td>
                            </tr>

                            <tr>
                                <td class="text-center fw-bold">B.</td>
                                <td class="fw-bold" colspan="3">Pengumpulan</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>3. Pengumpulan Data</td>
                                <td class="text-center">{{ formatTanggal($pengumpulan['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($pengumpulan['akhir'] ?? null) }}</td>
                            </tr>

                            <tr>
                                <td class="text-center fw-bold">C.</td>
                                <td class="fw-bold" colspan="3">Pemeriksaan</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>4. Pengolahan Data</td>
                                <td class="text-center">{{ formatTanggal($pengolahan['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($pengolahan['akhir'] ?? null) }}</td>
                            </tr>

                            <tr>
                                <td class="text-center fw-bold">D.</td>
                                <td class="fw-bold" colspan="3">Penyebarluasan</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>5. Analisis</td>
                                <td class="text-center">{{ formatTanggal($analisis['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($analisis['akhir'] ?? null) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>6. Diseminasi Hasil</td>
                                <td class="text-center">{{ formatTanggal($diseminasi['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($diseminasi['akhir'] ?? null) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>7. Evaluasi</td>
                                <td class="text-center">{{ formatTanggal($evaluasi['awal'] ?? null) }}</td>
                                <td class="text-center">{{ formatTanggal($evaluasi['akhir'] ?? null) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $variabels = [];
                        if (!empty($kegiatan->iii_variabel_yang_dikumpulkan)) {
                            $decoded = is_string($kegiatan->iii_variabel_yang_dikumpulkan)
                                ? json_decode($kegiatan->iii_variabel_yang_dikumpulkan, true)
                                : $kegiatan->iii_variabel_yang_dikumpulkan;

                            if (is_array($decoded)) {
                                $variabels = $decoded;
                            }
                        }
                    @endphp

                   <label class="top" style="font-weight: bold;">3.4. Variabel (Karakteristik) yang Dikumpulkan</label>
                   <br></br>
                    <table class="table-bordered" style="margin-bottom: 15px;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th class="text-center" style="width: 25%;">Nama Variabel (Karakteristik)</th>
                                <th class="text-center" style="width: 20%;">Konsep</th>
                                <th class="text-center" style="width: 40%;">Definisi</th>
                                <th class="text-center" style="width: 10%;">Referensi Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($variabels as $index => $var)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ strtoupper($var['nama'] ?? '-') }}</td>
                                    <td>{{ strtoupper($var['konsep'] ?? '-') }}</td>
                                    <td>{{ ($var['definisi'] ?? '-') }}</td>
                                    <td class="text-center">{{ $var['referensi_waktu'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada variabel yang dikumpulkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        
        <br class="page-break">

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header">IV. DESAIN KEGIATAN</th>
            </tr>
        </table>

        <table width="100%">
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>4.1. Kegiatan Ini Dilakukan:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_kegiatan_ini_dilakukan))) }}</label></div>

                    <div class="top"><label>4.2. Frekuensi Penyelenggaraan:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_frekuensi_penyelenggara))) }}</label></div>

                    <div class="top"><label>4.3. Tipe Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_tipe_pengumpulan_data))) }}</label></div>

                    <div class="top"><label>4.4. Cakupan Wilayah Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_cakupan_wilayah_pengumpulan_data))) }}</label></div>

                    <label class="top">4.5. Wilayah Kegiatan:</label>
                    @php
                        $wilayah = [];
                        $dataWilayah = $kegiatan->iv_sebagian_cakupan_wilayah_pengumpulan_data ?? null;

                        if ($dataWilayah) {
                            if (is_array($dataWilayah)) {
                                $wilayah = $dataWilayah;
                            } elseif (is_string($dataWilayah)) {
                                $decoded = json_decode($dataWilayah, true);
                                if (is_array($decoded)) {
                                    $wilayah = $decoded;
                                }
                            }
                        }
                    @endphp

                    <table class="table-bordered" style="margin-bottom: 15px;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th class="text-center" style="width: 35%;">Nama Provinsi</th>
                                <th class="text-center" style="width: 60%;">Kabupaten/Kota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wilayah as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ strtoupper($item['nama_provinsi'] ?? '-') }}</td>
                                    <td>{{ strtoupper($item['nama_kabupaten_kota'] ?? '-') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada wilayah kegiatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="top"><label>4.6. Metode Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_metode_pengumpulan_data))) }}</label></div>

                    <div class="top"><label>4.7. Sarana Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_sarana_pengumpulan_data))) }}</label></div>

                    <div class="top"><label>4.8. Unit Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_unit_pengumpulan_data ))) }}</label></div>
                </td>
            </tr>
        </table>

        <br class="page-break">

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header">VI. PENGUMPULAN DATA</th>
            </tr>
        </table>

        <table width="100%">
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>6.1. Apakah Melakukan Uji Coba (Pilot Survey)?</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_apakah_melakukan_uji_coba ))) }}</label></div>

                    <div class="top"><label>6.2. Metode Pemeriksaan Kualitas Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_metode_pemeriksaan_kualitas_pengumpulan_data ))) }}</label></div>

                    <div class="top"><label>6.3. Apakah Melakukan Penyesuaian Nonrespon?</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_apakah_melakukan_penyesuaian_nonrespon ))) }}</label></div>

                    <div class="top"><label>6.4. Petugas Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_petugas_pengumpulan_data))) }}</label></div>

                    <div class="top"><label>6.5. Persyaratan Pendidikan Terendah Petugas Pengumpulan Data:</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_persyaratan_pendidikan_terendah_petugas_pengumpulan_data))) }}</label></div>

                    <div class="top"><label>6.6. Jumlah Petugas:</label></div>
                    <div class="dobel" style="margin-bottom: 0;">
                        <label>Supervisor/penyelia/pengawas: {{ $kegiatan->vi_jumlah_petugas_supervisor }} orang</label>
                    </div>
                    <div class="isi">
                        <label>Pengumpul data/enumerator: {{ $kegiatan->vi_jumlah_petugas_enumerator}} orang</label>
                    </div>

                    <div class="top"><label>6.7. Apakah Melakukan Pelatihan Petugas?</label></div>
                    <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_apakah_melakukan_pelatihan_petugas ))) }}</label></div>
                </td>
            </tr>
        </table>

        <br class="page-break">

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header">VII. PENGOLAHAN DAN ANALISIS</th>
            </tr>
        </table>

        <table width="100%">
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    @php
                        $tahapan_raw = $kegiatan->vii_tahapan_pengolahan_data ?? '';
                        $tahapan = is_array($tahapan_raw)
                            ? $tahapan_raw
                            : explode(',', $tahapan_raw);
                        $tahapan = array_map('trim', $tahapan);

                        $daftar_tahapan = [
                            'Penyuntingan (Editing)' => 'Editing',
                            'Penyandian (Coding)' => 'Coding',
                            'Data Entry' => 'Data Entry',
                            'Penyahihan (Validasi)' => 'Validasi',
                        ];
                    @endphp

                    <div class="top">
                        <label>7.1. Tahapan Pengolahan Data:</label>
                    </div>
                    <div class="isi" style="line-height: 1.5;"> @foreach ($daftar_tahapan as $label => $value)
                            <div>{{ $label }} : {{ in_array($value, $tahapan) ? 'Ya' : 'Tidak' }}</div>
                        @endforeach
                    </div>

                    <div class="top"><label>7.2. Metode Analisis:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vii_metode_analisis))) }}</label>
                    </div>

                    <div class="top"><label>7.3. Unit Analisis:</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vii_unit_analisis))) }}</label>
                    </div>

                    <div class="top"><label>7.4. Tingkat Penyajian Hasil Analisis :</label></div>
                    <div class="isi">
                        <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vii_tingkat_penyajian_hasil_analisis))) }}</label>
                    </div>
                </td>
            </tr>
        </table>

        <br class="page-break">

        <table width="100%">
            <tr>
                <th height="30px" class="bg-header">VIII. DISEMINASI HASIL</th>
            </tr>
        </table>

        <table width="100%">
            <tr><td style="padding: 5px;"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="top"><label>8.1. Produk Kegiatan yang Tersedia untuk Umum</label></div>
                    <div class="dobel" style="margin-bottom: 0;">
                        <label>Tercetak (Hardcopy): {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_tercetak))) }}</label>
                    </div>
                    <div class="dobel" style="margin-bottom: 0;">
                        <label>Digital (Softcopy): {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_digital))) }} </label>
                    </div>
                    <div class="isi">
                        <label>Data Mikro: {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_mikrodata))) }}</label>
                    </div>

                    @php
                        use Carbon\Carbon;

                        function ambilTanggalRilis($data) {
                            if (is_string($data)) {
                                $decoded = json_decode($data, true);
                            } else {
                                $decoded = $data;
                            }
                            return $decoded[0] ?? null;
                        }

                        function tanggalIndo($tanggal) {
                            return $tanggal ? Carbon::parse($tanggal)->translatedFormat('d F Y') : '-';
                        }

                        $rilisTercetak = ambilTanggalRilis($kegiatan->viii_rencana_jadwal_rilis_produk_tercetak);
                        $rilisDigital = ambilTanggalRilis($kegiatan->viii_rencana_jadwal_rilis_produk_digital);
                        $rilisMikro = ambilTanggalRilis($kegiatan->viii_rencana_jadwal_rilis_produk_mikrodata);
                    @endphp

                    <div class="top"><label>8.2. Rencana Rilis Produk Kegiatan</label></div>

                    <table class="table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Produk</th>
                                <th class="text-center">Tanggal Rilis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tercetak (Hardcopy)</td>
                                <td class="text-center">{{ tanggalIndo($rilisTercetak) }}</td>
                            </tr>
                            <tr>
                                <td>Digital (Softcopy)</td>
                                <td class="text-center">{{ tanggalIndo($rilisDigital) }}</td>
                            </tr>
                            <tr>
                                <td>Data Mikro</td>
                                <td class="text-center">{{ tanggalIndo($rilisMikro) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>