<?php
namespace App\Exports;

use App\Models\DataMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MappingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $apiId;

    public function __construct($apiId)
    {
        $this->apiId = $apiId;
    }

    // Mengambil data mapping berdasarkan apiId
    public function collection()
    {
        return DataMapping::where('api_id', $this->apiId)->get();
    }

    // Menentukan headers berdasarkan semua key yang ditemukan di data
    public function headings(): array
    {
        $dataMapping = DataMapping::where('api_id', $this->apiId)->first();
        $jsonhasil = json_decode($dataMapping->jsonhasil, true);

        // Ambil semua key yang ada di data
        $headers = $this->getHeadersFromData($jsonhasil['data']);
        return $headers;
    }

    // Menyesuaikan data per baris untuk ekspor
    public function map($dataMapping): array
    {
        $jsonhasil = json_decode($dataMapping->jsonhasil, true);
        $dataRows = $jsonhasil['data']; 

        // Ambil semua headers yang sudah ditentukan
        $headers = $this->getHeadersFromData($dataRows);
        $mappedData = [];

        // Loop melalui setiap row dan pastikan setiap header ada
        foreach ($dataRows as $row) {
            $rowData = [];
            foreach ($headers as $header) {
                // Ambil nilai atau null jika header tersebut tidak ada di row
                $rowData[] = isset($row[$header]) ? $row[$header] : null;
            }
            $mappedData[] = $rowData;
        }

        return $mappedData;
    }

    // Mengambil semua headers dari key yang ada di data (semua key, tidak hanya di baris pertama)
    private function getHeadersFromData($data)
    {
        $headers = [];

        // Loop untuk mengambil semua key yang ada di setiap baris
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if (!in_array($key, $headers)) {
                    $headers[] = $key;
                }
            }
        }

        return $headers;
    }
}
