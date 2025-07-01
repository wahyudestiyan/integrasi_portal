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
       <a href="{{ route('var.downloadPdf', $msvar->id) }}" class="btn btn-danger" target="_blank">
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
<!-- HEADER -->
<div class="top"><label>Detail Metadata Variabel : {{ ucwords(strtolower(str_replace('_', ' ', $msvar->nama ))) }}</label></div>
<br></br>

<!--isi-->
<div class="top"><label>Nama :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->nama ))) }}</label>
</div>

<div class="top"><label>Alias :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->alias ))) }}</label>
</div>

<div class="top"><label>Definisi Variabel :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->definisi ))) }}</label>
</div>

<div class="top"><label>Konsep :</label></div>
<div class="isi">
    <label>{{ is_array($msvar->konsep) ? implode(', ', $msvar->konsep) : $msvar->konsep }}</label>
</div>

<div class="top"><label>Referensi Pemilihan :</label></div>
<div class="isi">
    <label>{{ is_array($msvar->referensi_pemilihan) ? implode(', ', $msvar->referensi_pemilihan) : $msvar->referensi_pemilihan }}</label>
</div>

<div class="top"><label>Referensi Waktu :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->referensi_waktu ))) }}</label>
</div>

<div class="top"><label>Ukuran :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->ukuran ))) }}</label>
</div>

<div class="top"><label>Satuan :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->satuan ))) }}</label>
</div>

<div class="top"><label>Tipe Data :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->tipe_data ))) }}</label>
</div>

<div class="top"><label>Isian Klasifikasi :</label></div>
<div class="isi">
    @if(is_array($msvar->value_domain) && count($msvar->value_domain) > 0)
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode Item Klasifikasi</th>
                    <th>Nama Item Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($msvar->value_domain as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['kode'] ?? '-' }}</td>
                        <td>{{ $item['nilai'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Tidak ada data isian klasifikasi.</p>
    @endif
</div>

<div class="top"><label>Aturan Validasi :</label></div>
<div class="isi">
     <label>{{ is_array($msvar->aturan_validasi) ? implode(', ', $msvar->aturan_validasi) : $msvar->aturan_validasi }}</label>
</div>

<div class="top"><label>Kalimat Pernyataan :</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->kalimat_pertanyaan ))) }}</label>
</div>

<div class="top"><label>Apakah Variabel Data Diakses Umum ?</label></div>
<div class="isi">
    <label>{{ ucwords(strtolower(str_replace('_', ' ', $msvar->apakah_variabel_bisa_diakses_umum ))) }}</label>
</div>
</div>
@endsection
