<?php

namespace App\Imports;

use App\Models\DataApi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataApiImport implements ToModel, WithHeadingRow
{
    /**
     * Membuat atau mengupdate data dari setiap baris Excel
     */
    public function model(array $row)
    {
        return DataApi::updateOrCreate(
            [
                // Kunci unik untuk deteksi duplikasi
                'id_api' => $row['id_api'],
                'instansi_token_id' => $row['instansi_token_id'],
            ],
            [
                // Kolom yang akan disimpan/diupdate
                'judul' => $row['judul'],
                'tahun_data' => $row['tahun_data'],
            ]
        );
    }
}
