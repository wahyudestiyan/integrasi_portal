<?php

namespace App\Exports;

use App\Models\RekapitulasiPemeriksaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use App\Models\DataPrioritasSkSekda;

class RekapitulasiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private $index = 0;

    public function collection()
    {
        $subQuery = DB::table('rekapitulasi_pemeriksaan')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('instansi_token_id');
    
        return RekapitulasiPemeriksaan::with('instansi')
            ->joinSub($subQuery, 'latest', function ($join) {
                $join->on('rekapitulasi_pemeriksaan.id', '=', 'latest.id');
            })
            ->select('rekapitulasi_pemeriksaan.*')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Instansi',
            'Data Prioritas SK Sekda',
            'Judul Data Prioritas Terdaftar',
            'Data Prioritas Masuk',
            'Status',
            'Keterangan',
        ];
    }

    public function map($rekap): array
    {
        // Ambil keterangan terkait dari tabel DataPrioritasSkSekda untuk instansi dan tahun yang sama
        $keterangan = DataPrioritasSkSekda::where('instansi_token_id', $rekap->instansi_token_id)
            ->where('tahun', $rekap->tahun)
            ->pluck('keterangan')
            ->filter()
            ->implode('; '); // Gabungkan keterangan menjadi satu string

        return [
            ++$this->index,
            optional($rekap->instansi)->nama_instansi ?? '—',
            $rekap->jumlah_sk_sekda,
            $rekap->jumlah_terdaftar_di_portal,
            $rekap->jumlah_data_terisi,
            $rekap->status ?? 'Belum Lengkap',
            $keterangan ?? '-', // Tampilkan keterangan dari DataPrioritasSkSekda
        ];
    }
}
