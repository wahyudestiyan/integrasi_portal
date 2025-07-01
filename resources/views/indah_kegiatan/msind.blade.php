@extends('layouts.app')

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
      <a href="{{ route('ind.downloadPdf', $msind->id) }}" class="btn btn-danger" target="_blank">
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
<div class="top"><label>Detail Metadata Indikator : {{ $msind->nama }}</label></div>
<br></br>

<!--isi-->
<div class="top"><label>Nama :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->nama ))) }}</label>
</div>

<div class="top"><label>Definisi Indikator:</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->definisi ))) }}</label>
</div>

<div class="top"><label>Konsep :</label></div>
<div class="isi">
    <label>{{ is_array($msind->konsep) ? implode(', ', $msind->konsep) : $msind->konsep }}</label>
</div>

<div class="top"><label>Interpretasi :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->interpretasi ))) }}</label>
</div>

<div class="top"><label>Metode Perhitungan :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->metode_perhitungan ))) }}</label>
</div>

<div class="top"><label>Rumus :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->rumus ))) }}</label>
</div>

<div class="top"><label>Ukuran :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->ukuran ))) }}</label>
</div>

<div class="top"><label>Satuan :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->satuan ))) }}</label>
</div>

<div class="top"><label>Klasifikasi Penyajian :</label></div>

<div class="isi">
    @php
        $items = is_string($msind->variabel_disaggregasi)
            ? json_decode($msind->variabel_disaggregasi, true)
            : $msind->variabel_disaggregasi;

        $items = is_array($items) ? $items : [];
    @endphp

    <label>
        {{ collect($items)->pluck('nama')->implode(', ') }}
    </label>
</div>



<div class="top"><label>Apakah Indikator Komposit? </label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->apakah_indikator_komposit ))) }}</label>
</div>

<div class="top"><label>Variabel Pembangun :</label></div>
<div class="isi">
    @php
        $variabelPembangun = is_string($msind->variabel_pembangun)
            ? json_decode($msind->variabel_pembangun, true)
            : $msind->variabel_pembangun;
    @endphp

    @if(is_array($variabelPembangun) && count($variabelPembangun) > 0)
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Variabel Pembangun</th>
                    <th>Kegiatan Penghasil Variabel Pembangun</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variabelPembangun as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['nama'] ?? '-' }}</td>
                        <td>
                            {{ is_array($item['kegiatan_penghasil'] ?? null) 
                                ? implode(', ', $item['kegiatan_penghasil']) 
                                : '-' 
                            }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Tidak ada Variabel Pembangun.</p>
    @endif
</div>


<div class="top"><label>Level Estimasi : </label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->level_estimasi ))) }}</label>
</div>

<div class="top"><label>Apakah Indikator Dapat Diakses Umum? </label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msind->apakah_indikator_bisa_diakses_umum ))) }}</label>
</div>
</div>
@endsection
