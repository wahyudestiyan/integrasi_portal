<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\DataPrioritasSkImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapitulasiExport;
use App\Models\InstansiToken;
use App\Models\DataPrioritasSkSekda;
use App\Models\RekapitulasiPemeriksaan;
use App\Models\DataPrioritasBelumTerisi;
use App\Exports\TemplateSKSekdaExport;



class PemeriksaanDataController extends Controller
{
    /**
     * Menampilkan form upload Excel
     */
    public function create()
    {
        return view('pemeriksaan.upload');
    }

    /**
     * Proses Upload dan Import Excel SK Sekda
     */
    // Unduh template Excel untuk SK Sekda
    public function downloadTemplate()
    {
        return Excel::download(new TemplateSKSekdaExport, 'template_sk_sekda.xlsx');
    }

    // Mengimpor file Excel
    public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    // Ambil semua data dari file Excel
    $collection = Excel::toCollection(new DataPrioritasSkImport, $request->file('file'))[0];

    $dataBaru = [];
    $idDataPortalExcel = [];

    foreach ($collection as $row) {
        $idDataPortal = $row['id_data_portal'];

        if (!$idDataPortal) continue;

        $idDataPortalExcel[] = $idDataPortal;

        $instansi = \App\Models\InstansiToken::where('nama_instansi', $row['nama_instansi'])->first();
        if (!$instansi) continue;

        // Update jika sudah ada, atau insert kalau belum ada
        DataPrioritasSkSekda::updateOrCreate(
            ['id_data_portal' => $idDataPortal],
            [
                'instansi_token_id' => $instansi->id,
                'judul_data' => $row['judul_data'],
                'tahun' => $row['tahun'],
                'updated_at' => now(), // pastikan timestamp update
            ]
        );
    }

    // Hapus data yang tidak ada di Excel
    DataPrioritasSkSekda::whereNotIn('id_data_portal', $idDataPortalExcel)->delete();

    return redirect()->back()->with('success', 'Data berhasil disinkronisasi dari file Excel.');
}

    /**
     * Menampilkan halaman rekap pemeriksaan (dengan filter tahun)
     */
    public function index(Request $request)
{
    $tahunList = DataPrioritasSkSekda::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
    $tahun = $request->get('tahun', date('Y')); // fallback ke tahun sekarang

    if (!$tahun) {
        $rekapitulasi = collect(); // tidak tampilkan data jika tidak ada tahun
    } else {
        $instansiIds = DataPrioritasSkSekda::where('tahun', $tahun)
            ->pluck('instansi_token_id')
            ->unique();

        $instansis = InstansiToken::whereIn('id', $instansiIds)->get();

        $rekapitulasi = $instansis->map(function ($instansi) use ($tahun) {
            $dataSk = DataPrioritasSkSekda::where('instansi_token_id', $instansi->id)
                ->where('tahun', $tahun)
                ->get();

            $rekap = RekapitulasiPemeriksaan::where('instansi_token_id', $instansi->id)
                ->where('tahun', $tahun)
                ->first();

            return (object)[
                'instansi' => $instansi,
                'jumlah_sk_sekda' => $dataSk->count(),
                'jumlah_terdaftar_di_portal' => $rekap->jumlah_terdaftar_di_portal ?? 0,
                'jumlah_data_terisi' => $rekap->jumlah_data_terisi ?? 0,
                'status' => $rekap->status ?? 'Belum Diperiksa',
                'tahun' => $tahun,
                'keterangan' => $dataSk->pluck('keterangan')->filter()->unique()->implode('; '),
            ];
        });
    }

    // Pagination manual
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10;
    $currentItems = $rekapitulasi->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginatedRekap = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $rekapitulasi->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('pemeriksaan.index', [
        'rekapitulasi' => $paginatedRekap,
        'tahun' => $tahun,
        'tahunList' => $tahunList,
    ]);
}


    public function periksa($instansiId, Request $request)
    {
        $tahun = $request->tahun;
        $instansi = InstansiToken::findOrFail($instansiId);
    
        $dataSkSekda = DataPrioritasSkSekda::where('instansi_token_id', $instansiId)
            ->where('tahun', $tahun)
            ->get();
    
        $jumlahSkSekda = $dataSkSekda->count();
        $apiData = $this->getDataFromApi($instansi, $tahun);
    
        $jumlahTerdaftar = 0;
        $jumlahDataTerisi = 0;
    
        // Hapus data belum terisi sebelumnya
        DataPrioritasBelumTerisi::where('instansi_token_id', $instansiId)
            ->where('tahun', $tahun)
            ->delete();
    
        foreach ($dataSkSekda as $data) {
            $matched = null;
    
            // 1. Cocokkan berdasarkan ID jika ada
            if ($data->id_data_portal) {
                $matched = collect($apiData)->firstWhere('id', $data->id_data_portal);
            }
    
            // 2. Jika tidak ada, cocokkan berdasarkan judul
            if (!$matched) {
                $matched = collect($apiData)->first(function ($item) use ($data) {
                    return Str::slug($item['judul']) === Str::slug($data->judul_data);
                });
            }
    
            // 3. Jika ditemukan
            if ($matched) {
                $jumlahTerdaftar++;
    
                $detail = $this->getDataDetailFromApi($matched['id'], $instansi);
                $detailTahunIni = collect($detail)->where('tahun_data', (int) $tahun);
    
                if ($detailTahunIni->isNotEmpty()) {
                    $jumlahDataTerisi++;
                } else {
                    DataPrioritasBelumTerisi::create([
                        'instansi_token_id' => $instansiId,
                        'judul_data' => $data->judul_data,
                        'tahun' => $tahun,
                        'keterangan' => 'Judul ditemukan, namun belum ada isian untuk tahun ' . $tahun,
                    ]);
                }
            } else {
                DataPrioritasBelumTerisi::create([
                    'instansi_token_id' => $instansiId,
                    'judul_data' => $data->judul_data,
                    'tahun' => $tahun,
                    'keterangan' => 'Judul tidak ditemukan di Portal Data',
                ]);
            }
        }
    
        // 🔍 Tentukan status
        if ($jumlahSkSekda === 0) {
            // Tidak ada SK Sekda
            $status = $jumlahDataTerisi === $jumlahTerdaftar ? 'Lengkap' : 'Belum Lengkap';
        } else {
            // Ada SK Sekda
            $status = (
                $jumlahSkSekda === $jumlahTerdaftar &&
                $jumlahTerdaftar === $jumlahDataTerisi
            ) ? 'Lengkap' : 'Belum Lengkap';
        }
    
        // 🔄 Simpan rekapitulasi
        RekapitulasiPemeriksaan::updateOrCreate(
            ['instansi_token_id' => $instansiId, 'tahun' => $tahun],
            [
                'jumlah_sk_sekda' => $jumlahSkSekda,
                'jumlah_terdaftar_di_portal' => $jumlahTerdaftar,
                'jumlah_data_terisi' => $jumlahDataTerisi,
                'status' => $status,
            ]
        );
    
        return redirect()->route('pemeriksaan.index', ['tahun' => $tahun])
            ->with('success', 'Pemeriksaan data berhasil disimpan untuk tahun ' . $tahun);
    }
    

    private function getDataFromApi($instansi, $tahun)
    {
        $client = new \GuzzleHttp\Client();
        $allData = [];
        $currentPage = 1;

        // Mengambil data secara berulang hingga semua halaman terambil
        do {
            // Kirim request ke API
            $response = $client->get('https://satudata.jatengprov.go.id/v1/data', [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($instansi->bearer_token), // Pastikan token benar
                ],
                'query' => [
                    'tahun' => $tahun,
                    'page' => $currentPage,
                    'per-page' => 100, // atau sesuai default API
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);
            $data = $responseData['data'] ?? [];
            $meta = $responseData['_meta'] ?? [];

            // Gabungkan data yang sudah diambil dengan data sebelumnya
            $allData = array_merge($allData, $data);

            // Periksa apakah masih ada halaman berikutnya
            $currentPage++;
        } while (isset($meta['pageCount']) && $currentPage <= $meta['pageCount']); // Lanjutkan jika masih ada halaman berikutnya

        return $allData;
    }

    private function getDataDetailFromApi($id, $instansi)
    {
        $client = new \GuzzleHttp\Client();
        $allDetailData = [];
        $currentPage = 1;

        // Mengambil data detail untuk setiap ID yang diberikan
        do {
            // Kirim request ke API untuk mengambil detail data
            $response = $client->get('https://satudata.jatengprov.go.id/v1/data/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . trim($instansi->bearer_token),
                ],
                'query' => [
                    'page' => $currentPage,
                    'per-page' => 100, // atau sesuai default API
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);
            $dataDetail = $responseData['data'] ?? [];
            $meta = $responseData['_meta'] ?? [];

            // Gabungkan data detail yang sudah diambil
            $allDetailData = array_merge($allDetailData, $dataDetail);

            // Periksa apakah masih ada halaman berikutnya
            $currentPage++;
        } while (isset($meta['pageCount']) && $currentPage <= $meta['pageCount']); // Lanjutkan jika ada halaman berikutnya

        return $allDetailData;
    }

    /**
     * Menampilkan daftar data yang belum dilengkapi
     */
    public function lihatBelumLengkap($instansiId, $tahun)
    {
        // Ambil data dari tabel data_prioritas_belum_terisi berdasarkan instansi_id dan tahun
        $dataBelum = DataPrioritasBelumTerisi::where('instansi_token_id', $instansiId)
                                            ->where('tahun', $tahun)
                                            ->get(); // Ambil semua data yang sesuai
    
        return view('pemeriksaan.lihat_belum', compact('dataBelum'));
    }
    public function lihatJudul($instansiId, $tahun)
    {
        // Ambil semua judul lengkap dari sk sekda
        $dataLengkap = DataPrioritasSkSekda::where('instansi_token_id', $instansiId)
            ->where('tahun', $tahun)
            ->get();
    
        // Ambil semua data belum terisi (judul + keterangan)
        $dataBelumTerisi = DataPrioritasBelumTerisi::where('instansi_token_id', $instansiId)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('judul_data'); // agar mudah dicari berdasarkan judul
    
        // Tandai status & keterangan
        foreach ($dataLengkap as $data) {
            if ($dataBelumTerisi->has($data->judul_data)) {
                $data->status = 'Belum Lengkap';
                $data->keterangan = $dataBelumTerisi[$data->judul_data]->keterangan;
            } else {
                $data->status = 'Lengkap';
                $data->keterangan = '-';
            }
        }
    
        return view('pemeriksaan.lihat_judul', compact('dataLengkap'));
    }
    
    public function export()
    {
        return Excel::download(new RekapitulasiExport, 'rekapitulasi.xlsx');
    }
    
}
