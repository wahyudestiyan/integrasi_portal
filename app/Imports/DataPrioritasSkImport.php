<?php

namespace App\Imports;

use App\Models\DataPrioritasSkSekda;
use App\Models\InstansiToken;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPrioritasSkImport implements ToModel, WithHeadingRow
{
    public $uploadedIds = []; // 🆕 Tampung ID yang diupload

    public function model(array $row)
    {
        $instansi = InstansiToken::where('nama_instansi', $row['nama_instansi'])->first();

        if (!$instansi) return null;

        $this->uploadedIds[] = $row['id_data_portal']; // Simpan ID

        return DataPrioritasSkSekda::updateOrCreate(
            ['id_data_portal' => $row['id_data_portal']],
            [
                'instansi_token_id' => $instansi->id,
                'judul_data' => $row['judul_data'],
                'tahun' => $row['tahun'],
                'keterangan' => $row['keterangan'] ?? null,
            ]
        );
    }

}
