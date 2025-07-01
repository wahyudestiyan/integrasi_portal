@extends('layouts.app') {{-- Atau layout utama kamu --}}

@section('content')

<style>

/* Container utama metadata */
.full-width-box {
    margin-left: 0; /* biar konsisten */
    padding-left: 1.25rem; /* sama dengan px-5 */
    padding-right: 1.25rem;
    background-color: white;
    box-sizing: border-box;
    overflow-x: auto;
}

.card-detail {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    position: relative;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 12px;
    border-radius: 15px;
    color: white;
    font-size: 12px;
    font-weight: bold;
}


/* Kotak isi utama */
.box {
    width: 100%;
    padding: 0;
    margin: 0;
    background-color: #fff;
    box-sizing: border-box;
}


/* Heading */
h1 {
    font-weight: bold;
    font-size: 28px; /* Ukuran font di sini */
    line-height: 1.2;
    margin: 20px 0;
    text-align: center;
}

/* Label struktur */
.judul label,
.isitabel label,
.top label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.isi label,
.isian label,
.dobel label {
    font-weight: normal;
    display: block;
    margin-bottom: 5px;
}

.isi label {
    border-bottom: 1px solid #ccc;
    text-align: justify;
    padding-bottom: 5px;
}

/* Spasi antar elemen */
.detail {
    margin-bottom: 15px;
}

/* Header tabel */
th.bg-header, th.bg-judul {
    background-color: #ccc;
    height: 30px;
    text-align: center;
}

/* Tabel isi */
table.b {
    border-collapse: collapse;
    width: 100%;
}

th.t, td.d, td.e {
    border: 1px solid #ccc;
    padding: 6px;
}

th.t, td.e {
    text-align: center;
}

td.d {
    text-align: left;
}

/* Tombol */
.btn-active {
    background-color: green;
    color: white;
    border: none;
    padding: 6px 12px;
    cursor: pointer;
}

.btn-inactive {
    background-color: white;
    color: green;
    border: 1px solid green;
    padding: 6px 12px;
    cursor: pointer;
}
.no-border-button {
    background: none;
    border: none;
    padding: 0;
    color: #0d6efd; /* biru seperti link */
    text-decoration: underline;
    cursor: pointer;
}


/* Responsif */
@media (max-width: 768px) {
    .box {
        padding: 10px;
    }

    table.b th, table.b td {
        font-size: 12px;
    }
}
</style>


@php
    $colors = ['warning', 'info', 'danger', 'default'];
    $boxColor = $colors[array_rand($colors)];
@endphp
<div class="full-width-box">
<div class="box box-{{ $boxColor }}">


{{-- Tombol Download PDF --}}
<p class="text-end">
    <a href="{{ route('indah-kegiatan.downloadPdf', $kegiatan->id) }}" class="btn btn-danger" target="_blank">
        Download PDF
    </a>
</p>

    <table>
        <tr><td height="25px"></td></tr>
    </table>
<div class="card-detail position-relative">
    <!-- Badge di pojok kanan atas -->
    @if($kegiatan->status == 'APPROVED')
        <span class="status-badge bg-success">Disetujui</span>
    @elseif($kegiatan->status == 'SUBMIT')
        <span class="status-badge bg-danger">Submit</span>
    @else
        <span class="status-badge bg-secondary">Draft</span>
    @endif
   <!-- AWAL HALAMAN METADATA STATISTIK -->
<!-- HEADER -->
<h1 class="text-center">METADATA STATISTIK</h1>
<h1 class="text-center">KEGIATAN</h1>

<!-- JUDUL KEGIATAN, TAHUN, CARA PENGUMPULAN, SEKTOR KEGIATAN -->
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

<!-- PENYELENGGARA -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header" colspan="2">I. PENYELENGGARA</th>
    </tr>
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
            <div class="top"><label>1.1. Instansi Penyelenggara:</label></div>
            <div class="isi">
                <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_instansi_penyelanggara))) }}</label>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="top"><label>1.2. Alamat Lengkap Instansi Penyelenggara:</label></div>
            <div class="isi">
                <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_alamat))) }}</label>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="detail">
                <table width="100%">
                    <tr>
                        <td>
                            <div class="top"><label>Telepon:</label></div>
                            <div class="isi">
                                <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_telepon))) }}</label>
                            </div>
                        </td>
                        <td>
                            <div class="top"><label>Faksmile:</label></div>
                            <div class="isi">
                                <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->i_faksimile))) }}</label>
                            </div>
                        </td>
                        
                        <td>
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

<!-- PENANGGUNG JAWAB -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header" colspan="2">II. PENANGGUNG JAWAB</th>
    </tr>
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
            <div class="top"><label>2.1. Unit Eselon Penanggung Jawab:</label></div>
            <div class="detail">
                <table width="100%">
                    <tr>
                        <td>
                            <div class="top"><label>Eselon 1:</label></div>
                            <div class="isi">
                                <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_unit_eselon1))) }}</label>
                            </div>
                        </td>
                        <td>
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
        <td>
            <div class="top"><label>2.2. Penanggung Jawab Teknis (setingkat Eselon 3):</label></div>
            <div class="detail">
                <table width="100%">
                    <tr>
                        <td>
                            <div class="top"><label>Nama:</label></div>
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
                            <td>
                                <div class="top"><label>Telepon:</label></div>
                                <div class="isi">
                                    <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_telepon))) }}</label>
                                </div>
                            </td>
                            <td>
                                <div class="top"><label>Faksmile:</label></div>
                                <div class="isi">
                                    <label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->ii_pj_faksimile))) }}</label>
                                </div>
                            </td>
                            <td>
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

<!-- PERENCANAAN DAN PERSIAPAN -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header">III. PERENCANAAN DAN PERSIAPAN</th>
    </tr>
</table>

<table width="100%">
    <tr><td height="10px"></td></tr>

    <tr>
        <td>
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
    <label><strong>3.3. Rencana Jadwal Kegiatan:</strong></label>
</div>

<table class="table table-bordered">
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


<table width="100%">
    <tr><td height="15px"></td></tr>
</table>

<!-- 3.4. Variabel (Karakteristik) yang Dikumpulkan -->
@php
    $variabels = [];

    // Langsung pakai jika sudah array
    if (!empty($kegiatan->iii_variabel_yang_dikumpulkan)) {
        $decoded = is_string($kegiatan->iii_variabel_yang_dikumpulkan)
            ? json_decode($kegiatan->iii_variabel_yang_dikumpulkan, true)
            : $kegiatan->iii_variabel_yang_dikumpulkan;

        if (is_array($decoded)) {
            $variabels = $decoded;
        }
    }
@endphp
<div class="mb-3">
    <label><strong>3.4. Variabel (Karakteristik) yang Dikumpulkan:</strong></label>
</div>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th class="text-center" style="width: 30%;">Nama Variabel (Karakteristik)</th>
            <th class="text-center" style="width: 20%;">Konsep</th>
            <th class="text-center" style="width: 30%;">Definisi</th>
            <th class="text-center" style="width: 15%;">Referensi Waktu</th>
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



<!-- IV. DESAIN KEGIATAN -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header">IV. DESAIN KEGIATAN</th>
    </tr>
</table>

<table width="100%">
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
            <div class="top"><label>4.1. Kegiatan Ini Dilakukan:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_kegiatan_ini_dilakukan))) }}</label></div>

            <div class="top"><label>4.2. Frekuensi Penyelenggaraan:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_frekuensi_penyelenggara))) }}</label></div>

            <div class="top"><label>4.3. Tipe Pengumpulan Data:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_tipe_pengumpulan_data))) }}</label></div>

            <div class="top"><label>4.4. Cakupan Wilayah Pengumpulan Data:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_cakupan_wilayah_pengumpulan_data))) }}</label></div>

            <div class="isitabel"><label>4.5. Wilayah Kegiatan:</label></div>
        </td>
    </tr>
</table>

@php
    $wilayah = [];

    $dataWilayah = $kegiatan->iv_sebagian_cakupan_wilayah_pengumpulan_data ?? null;

    if ($dataWilayah) {
        // Jika sudah array
        if (is_array($dataWilayah)) {
            $wilayah = $dataWilayah;
        }

        // Jika masih string JSON
        elseif (is_string($dataWilayah)) {
            $decoded = json_decode($dataWilayah, true);
            if (is_array($decoded)) {
                $wilayah = $decoded;
            }
        }
    }
@endphp


<!-- <div class="mb-3">
    <label><strong>4.5. Wilayah Kegiatan:</strong></label>
</div> -->

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th class="text-center" style="width: 30%;">Nama Provinsi</th>
            <th class="text-center" style="width: 65%;">Kabupaten/Kota</th>
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


<table width="100%">
    <tr><td height="15px"></td></tr>
</table>

<table width="100%">
    <tr>
        <td>
            <div class="top"><label>4.6. Metode Pengumpulan Data:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_metode_pengumpulan_data))) }}</label></div>

            <div class="top"><label>4.7. Sarana Pengumpulan Data:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_sarana_pengumpulan_data))) }}</label></div>

            <div class="top"><label>4.8. Unit Pengumpulan Data:</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->iv_unit_pengumpulan_data ))) }}</label></div>
        </td>
    </tr>
</table>

<!-- VI. PENGUMPULAN DATA -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header">VI. PENGUMPULAN DATA</th>
    </tr>
</table>

<table width="100%">
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
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
            <div class="dobel">
                <label>Supervisor/penyelia/pengawas: {{ $kegiatan->vi_jumlah_petugas_supervisor }} orang</label>
            </div>
            <div class="isi">
                <label>Pengumpul data/enumerator: {{ $kegiatan->vi_jumlah_petugas_enumerator}}  orang</label>
            </div>

            <div class="top"><label>6.7. Apakah Melakukan Pelatihan Petugas?</label></div>
            <div class="isi"><label>{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->vi_apakah_melakukan_pelatihan_petugas ))) }}</label></div>
        </td>
    </tr>
</table>

<!-- VII. PENGOLAHAN DAN ANALISIS -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header">VII. PENGOLAHAN DAN ANALISIS</th>
    </tr>
</table>

<table width="100%">
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
            @php
    $tahapan_raw = $kegiatan->vii_tahapan_pengolahan_data ?? '';
    $tahapan = is_array($tahapan_raw)
        ? $tahapan_raw
        : explode(',', $tahapan_raw); // <-- Pecah string jadi array

    // Hilangkan spasi berlebih di setiap elemen
    $tahapan = array_map('trim', $tahapan);

    $daftar_tahapan = [
        'Penyuntingan (Editing)' => 'Editing',
        'Penyandian (Coding)' => 'Coding',
        'Data Entry' => 'Data Entry',
        'Penyahihan (Validasi)' => 'Validasi',
    ];
@endphp

<div class="top">
    <label><strong>7.1. Tahapan Pengolahan Data:</strong></label>
</div>
<div class="isi" style="line-height: 1.8;">
    @foreach ($daftar_tahapan as $label => $value)
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
<!-- VIII. DISEMINASI HASIL -->
<table width="100%">
    <tr>
        <th height="30px" class="bg-header">VIII. DISEMINASI HASIL</th>
    </tr>
</table>

<table width="100%">
    <tr><td height="10px"></td></tr>
    <tr>
        <td>
            <div class="top"><label>8.1. Produk Kegiatan yang Tersedia untuk Umum</label></div>
            <div class="dobel">
                <label>Tercetak (Hardcopy): {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_tercetak))) }}</label>
            </div>
            <div class="dobel">
                <label>Digital (Softcopy): {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_digital))) }} </label>
            </div>
            <div class="isi">
                <label>Data Mikro: {{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->viii_ketersediaan_produk_mikrodata))) }}</label>
            </div>

        </td>
    </tr>
</table>

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


<div class="mb-3">
    <label><strong>8.2. Rencana Rilis Produk Kegiatan</strong></label>
</div>
<table class="table table-bordered">
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

</div>
</div>
@endsection
