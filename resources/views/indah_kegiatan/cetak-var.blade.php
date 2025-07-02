<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Metadata Variabel</title>
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
<h1 class="text-center">METADATA VARIABEL</h1>

<div class="detail-row">
    <div class="detail-label">Metadata Kegiatan:</div>
    <div class="detail-value">{{ ucwords(strtolower(str_replace('_', ' ', $kegiatan->judul_kegiatan ))) }}</div>
</div>

<div class="detail-row">
    <div class="detail-label">Detail Metadata Variabel:</div>
    <div class="detail-value">{{ ucwords(strtolower(str_replace('_', ' ', $msvar->nama ))) }}</div>
</div>
<hr>
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
        <div class="table-responsive mt-2">
            <table class="table-custom">
                <thead>
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
        </div>
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
</body>
</html>