<?php

namespace App\Exports;

use App\Models\DataApi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataMonitoringExport implements FromCollection, WithHeadings, WithMapping
{
    protected $instansiId;

    public function __construct($instansiId)
    {
        $this->instansiId = $instansiId;
    }

    /**
     * Ambil data dari database
     */
    public function collection()
    {
        return DataApi::where('instansi_token_id', $this->instansiId)
            ->select('id_api', 'judul', 'tahun_data')
            ->get();
    }

    /**
     * Ubah tampilan setiap baris sebelum diekspor
     */
    public function map($row): array
    {
        return [
            'https://satudata.jatengprov.go.id/v1/data/' . $row->id_api,
            $row->judul,
            $row->tahun_data,
        ];
    }

    /**
     * Judul kolom di Excel
     */
    public function headings(): array
    {
        return [
            'Link API',
            'Judul',
            'Tahun Data',
        ];
    }
}
