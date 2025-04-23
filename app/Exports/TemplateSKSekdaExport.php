<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateSKSekdaExport implements FromArray, WithHeadings
{
    /**
     * Header kolom di Excel
     */
    public function headings(): array
    {
        return [
            'nama_instansi',
            'judul_data',
            'id_data_portal',
            'tahun', 
            'keterangan',
        ];
    }

    /**
     * Contoh baris (bisa dikosongkan kalau tidak mau isi data contoh)
     */
    public function array(): array
    {
        return [
        
        ];
    }
}
