<?php

namespace App\Imports;

use App\Models\DataApi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataApiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Periksa apakah id_api sudah ada di database
        return DataApi::firstOrCreate(
            ['id_api' => $row['id_api']], // Cari berdasarkan id_api
            [
                'instansi_token_id' => $row['instansi_token_id'],
                'judul' => $row['judul'],
                'tahun_data' => $row['tahun_data'],
            ]
        );
    }
}
