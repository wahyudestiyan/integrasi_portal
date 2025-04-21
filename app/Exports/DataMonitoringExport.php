<?php

namespace App\Exports;

use App\Models\DataApi; // Pastikan model yang digunakan sesuai
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataMonitoringExport implements FromCollection, WithHeadings
{
    protected $instansiId;

    public function __construct($instansiId)
    {
        $this->instansiId = $instansiId;
    }

    /**
     * Mengambil data berdasarkan instansi
     */
    public function collection()
    {
        // Menggunakan instansi_token_id untuk filter data
        return DataApi::where('instansi_token_id', $this->instansiId)
            ->select('id_api', 'judul', 'tahun_data')
            ->get();
    }
    

    /**
     * Menambahkan heading (judul kolom) di file Excel
     */
    public function headings(): array
    {
        return [
            'ID API',
            'Judul',
            'Tahun Data',
        ];
    }
}
