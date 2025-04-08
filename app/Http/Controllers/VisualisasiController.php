<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstansiToken;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InstansiImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisualisasiController extends Controller
{

    public function create()
    {
        $instansiTokens = InstansiToken::all();
        return view('visualisasi.create', compact('instansiTokens'));
    }
    

    public function downloadTemplate()
    {
        $headers = ['Nama Instansi', 'Bearer Token'];
        $filename = 'template_instansi_token.xlsx';

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array { return [$this->headers]; }
        }, $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $data = Excel::toArray([], $request->file('file'))[0];

        $duplicates = [];

        foreach (array_slice($data, 1) as $row) {
            if (count($row) < 2 || empty($row[0])) { // Minimal harus ada dua kolom
                continue;
            }

            $existingData = InstansiToken::where('nama_instansi', $row[0])->first();

            if ($existingData) {
                $existingData->update([
                    'bearer_token' => $row[1] ?? $existingData->bearer_token,
                ]);
                $duplicates[] = ['nama_instansi' => $row[0]];
            } else {
                InstansiToken::create([
                    'nama_instansi' => $row[0] ?? null,
                    'bearer_token' => $row[1] ?? null,
                ]);
            }
        }

        if (count($duplicates) > 0) {
            $duplicateText = collect($duplicates)->map(function ($row) {
                return 'Instansi: ' . $row['nama_instansi'];
            })->implode(' | ');

            return redirect()->route('visualisasi.create')->with('warning', 'Data duplikat diperbarui: ' . $duplicateText);
        }

        return redirect()->route('visualisasi.create')->with('success', 'Data berhasil diimpor.');
    }

    public function index(Request $request)
{
    $instansiTokens = InstansiToken::all();
    $instansiId = $request->query('instansiId');
    $data = [];

    if ($instansiId) {
        $instansi = InstansiToken::find($instansiId);
        if ($instansi) {
            $allData = [];
            $page = 1;

            do {
                $response = Http::withToken($instansi->bearer_token)
                    ->get('https://satudata.jatengprov.go.id/v1/data', [
                        'page' => $page
                    ]);

                if ($response->failed()) {
                    return redirect()->back()->with('error', 'Gagal mengambil data dari API');
                }

                $result = $response->json();

                if (!isset($result['data']) || !isset($result['_meta'])) {
                    return redirect()->back()->with('error', 'Format response API tidak sesuai');
                }

                // 🔹 Gabungkan data dari API
                $allData = array_merge($allData, $result['data']);

                // 🔹 Debugging: Log jumlah data yang diterima di setiap halaman
                Log::info("Halaman {$page} data API:", ['jumlah_data' => count($result['data'])]);

                $page++;
            } while ($result['_meta']['currentPage'] < $result['_meta']['pageCount']);

            // 🔹 Ambil ID dan Judul
            $data = collect($allData)->map(fn($item) => [
                'id' => $item['id'],
                'judul' => $item['judul']
            ]);
        }
    }

    return view('visualisasi.index', compact('instansiTokens', 'data', 'instansiId'));
}
    
    public function show($instansiId, $dataId)
{
    $instansi = InstansiToken::findOrFail($instansiId);
    $instansiTokens = InstansiToken::all();
    $allData = [];
    $judul = 'Tidak ada Judul'; // Default jika tidak ditemukan
    $detailData = []; // Variabel untuk menampung detail data yang diambil dari API kedua
    $page = 1;

    // 🔹 1. Ambil semua data judul dari API pertama (Pastikan paginasi berjalan)
    if ($instansi) {
        do {
            $response = Http::withToken($instansi->bearer_token)
                ->get('https://satudata.jatengprov.go.id/v1/data', [
                    'page' => $page
                ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Gagal mengambil data dari API');
            }

            $result = $response->json();

            if (!isset($result['data'])) {
                return redirect()->back()->with('error', 'Format response API tidak sesuai');
            }

            // 🔹 2. Gabungkan hasil halaman ke dalam array utama
            $allData = array_merge($allData, $result['data'] ?? []);

            Log::info("Halaman {$page} data API pertama:", $result['data']);

            $page++;
        } while (isset($result['next_page_url']) && $result['next_page_url'] != null);

        // 🔹 3. Ambil hanya ID dan Judul dari API pertama
        $data = collect($allData)->map(fn($item) => [
            'id' => $item['id'],
            'judul' => $item['judul']
        ]);

        // 🔹 4. Ambil judul data berdasarkan dataId
        $judulData = collect($allData)->firstWhere('id', $dataId);
        if ($judulData) {
            $judul = $judulData['judul'] ?? 'Tidak ada Judul';
        }
    }

    // 🔹 5. Ambil detail data dari API kedua berdasarkan dataId
    $detailData = [];
    $page = 1;

    do {
        $response = Http::withToken($instansi->bearer_token)
            ->get("https://satudata.jatengprov.go.id/v1/data/{$dataId}", [
                'page' => $page
            ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Gagal mengambil data dari API');
        }

        $result = $response->json();

        if (!isset($result['data']) || !isset($result['_meta'])) {
            return redirect()->back()->with('error', 'Format response API tidak sesuai');
        }

        // 🔹 6. Pastikan data detail ditambahkan dengan benar
        $detailData = array_merge($detailData, $result['data'] ?? []);
        Log::info("Halaman {$page} data API kedua:", $result['data']);

        $page++;
    } while ($result['_meta']['currentPage'] < $result['_meta']['pageCount']);

    // 🔹 7. Kirim data ke view
    return view('visualisasi.detail', compact('instansiTokens', 'data', 'detailData', 'instansi', 'judul'));
}

public function tabulasi($instansiId, $dataId)
{
    $instansi = InstansiToken::findOrFail($instansiId);
    $instansiTokens = InstansiToken::all();
    $allData = [];
    $judul = 'Tidak ada Judul'; // Default jika tidak ditemukan
    $detailData = []; // Variabel untuk menampung detail data yang diambil dari API kedua
    $page = 1;

    // 🔹 1. Ambil semua data judul dari API pertama (Pastikan paginasi berjalan)
    if ($instansi) {
        do {
            $response = Http::withToken($instansi->bearer_token)
                ->get('https://satudata.jatengprov.go.id/v1/data', [
                    'page' => $page
                ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'Gagal mengambil data dari API');
            }

            $result = $response->json();

            if (!isset($result['data'])) {
                return redirect()->back()->with('error', 'Format response API tidak sesuai');
            }

            // 🔹 2. Gabungkan hasil halaman ke dalam array utama
            $allData = array_merge($allData, $result['data'] ?? []);

            Log::info("Halaman {$page} data API pertama:", $result['data']);

            $page++;
        } while (isset($result['next_page_url']) && $result['next_page_url'] != null);

        // 🔹 3. Ambil hanya ID dan Judul dari API pertama
        $data = collect($allData)->map(fn($item) => [
            'id' => $item['id'],
            'judul' => $item['judul']
        ]);

        // 🔹 4. Ambil judul data berdasarkan dataId
        $judulData = collect($allData)->firstWhere('id', $dataId);
        if ($judulData) {
            $judul = $judulData['judul'] ?? 'Tidak ada Judul';
        }
    }

    // 🔹 5. Ambil detail data dari API kedua berdasarkan dataId
    $detailData = [];
    $page = 1;

    do {
        $response = Http::withToken($instansi->bearer_token)
            ->get("https://satudata.jatengprov.go.id/v1/data/{$dataId}", [
                'page' => $page
            ]);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Gagal mengambil data dari API');
        }

        $result = $response->json();

        if (!isset($result['data']) || !isset($result['_meta'])) {
            return redirect()->back()->with('error', 'Format response API tidak sesuai');
        }

        // 🔹 6. Pastikan data detail ditambahkan dengan benar
        $detailData = array_merge($detailData, $result['data'] ?? []);
        Log::info("Halaman {$page} data API kedua:", $result['data']);

        $page++;
    } while ($result['_meta']['currentPage'] < $result['_meta']['pageCount']);

    // 🔹 7. Kirim data ke view
    return view('visualisasi.tabulasi', compact('instansiTokens', 'data', 'detailData', 'instansi', 'judul'));
}



}
