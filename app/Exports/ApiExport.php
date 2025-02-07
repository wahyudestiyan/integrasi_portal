<?php

namespace App\Exports;

use App\Models\Api;
use Maatwebsite\Excel\Concerns\FromCollection;

class ApiExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Api::select('nama_instansi', 'nama_data', 'url_api', 'method', 'status')->get();
    }

    public function headings(): array
    {
        return ["Nama Instansi", "Nama Data", "URL API", "Method", "Status"];
    }
}
