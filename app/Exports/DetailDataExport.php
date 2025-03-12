<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailDataExport implements FromCollection, WithHeadings
{
    protected $detailData;

    // Constructor untuk menerima data detail
    public function __construct($detailData)
    {
        $this->detailData = $detailData;
    }

    // Fungsi untuk mengambil koleksi data
    public function collection()
    {
        return collect($this->detailData);
    }

    // Fungsi untuk menetapkan judul kolom di Excel
    public function headings(): array
    {
        if (count($this->detailData) > 0) {
            return array_keys($this->detailData[0]);
        }
        return [];
    }
}

