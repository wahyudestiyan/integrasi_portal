<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Metadata Indikator</title>
   <style>
/* ====== CONTAINER ====== */
.full-width-box {
    margin-left: 0;
    padding: 1.5rem 1.5rem;
    background-color: #ffffff;
    box-sizing: border-box;
    overflow-x: auto;
    font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
    color: #1F2937;
    font-size: 14px;
    line-height: 1.6;
}

/* ====== CARD WRAPPER ====== */
.card-detail {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* ====== BADGE STATUS ====== */
.status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 14px;
    border-radius: 20px;
    color: white;
    font-size: 12px;
    font-weight: 600;
    background-color: #28a745;
}

/* ====== SECTION BOX ====== */
.box {
    width: 100%;
    background-color: #fff;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ====== HEADINGS ====== */
h1 {
    font-weight: 700;
    font-size: 28px;
    line-height: 1.3;
    margin: 25px 0;
    text-align: center;
    color: #2c3e50;
}

/* ====== LABEL STRUKTUR ====== */
.judul label,
.isitabel label,
.top label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
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
    color: #444;
}

/* ====== TABEL ====== */
table.b {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
}

th.bg-header, th.bg-judul {
    background-color: #e2e2e2;
    text-align: center;
    padding: 8px;
    font-weight: 600;
}

th.t, td.d, td.e {
    border: 1px solid #ccc;
    padding: 10px;
    vertical-align: middle;
}

td.d {
    text-align: left;
}

td.e,
th.t {
    text-align: center;
}

/* ====== TOMBOL ====== */
.btn-active {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 7px 14px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-inactive {
    background-color: #fff;
    color: #28a745;
    border: 1px solid #28a745;
    padding: 7px 14px;
    border-radius: 4px;
    cursor: pointer;
}

.no-border-button {
    background: none;
    border: none;
    padding: 0;
    color: #0d6efd;
    text-decoration: underline;
    cursor: pointer;
}

/* ====== FLEX ROW FOR LABEL-VALUE PAIRS ====== */
.detail-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.detail-label {
    width: 220px;
    font-weight: bold;
    color: #333;
}

.detail-value {
    flex: 1;
    color: #555;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 768px) {
    .full-width-box {
        padding: 1rem;
    }

    .detail-label {
        width: 100%;
        margin-bottom: 4px;
    }

    .detail-value {
        width: 100%;
    }

    table.b th,
    table.b td {
        font-size: 13px;
        padding: 6px;
    }
    .table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 14px;
}

.table-custom th,
.table-custom td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

.table-custom th {
    background-color: #f0f0f0;
    font-weight: bold;
    text-align: center;
}

.table-custom tbody tr:nth-child(even) {
    background-color: #fafafa;
}

}
</style>

</head>
<body>

<h1 class="text-center">METADATA INDIKATOR</h1>

<div class="detail-row">
    <div class="detail-label">Metadata Kegiatan:</div>
    <div class="detail-value">{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->judul_kegiatan ))) }}</div>
</div>

<div class="detail-row">
    <div class="detail-label">Detail Metadata Indikator:</div>
    <dv class="detail-value">{{ ucwords(strtolower(str_replace('_', ' ', $msind->nama ))) }}</div>
</div>
<hr>
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
        <div class="table-responsive mt-2">
            <table class="table-custom">
                <thead>
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
        </div>
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
</body>
</html>
