<?php

namespace App\Exports;

use App\Models\ApiBps;
use Maatwebsite\Excel\Concerns\FromCollection;

class ApiBpsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /**
    * Mengambil data dari model ApiBps
    */
    public function collection()
    {
        return ApiBps::select('nama_instansi', 'nama_data', 'url_api', 'method', 'status')->get();
    }

    /**
    * Menambahkan header di file Excel
    */
    public function headings(): array
    {
        return ["Nama Instansi", "Nama Data", "URL API", "Method", "Status"];
    }
}
