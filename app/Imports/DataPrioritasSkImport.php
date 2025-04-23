<?php

namespace App\Imports;

use App\Models\DataPrioritasSkSekda;
use App\Models\InstansiToken;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPrioritasSkImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari instansi berdasarkan nama
        $instansi = InstansiToken::where('nama_instansi', $row['nama_instansi'])->first();

        // Cek jika instansi ditemukan
        if (!$instansi) {
            return null; // Skip jika tidak ditemukan
        }

        return new DataPrioritasSkSekda([
            'instansi_token_id' => $instansi->id,
            'judul_data' => $row['judul_data'],
            'id_data_portal' => $row['id_data_portal'],
            'tahun' => $row['tahun'],
            'keterangan' => $row['keterangan'], // ✅ tambahkan ini
        ]);
        
    }
}
