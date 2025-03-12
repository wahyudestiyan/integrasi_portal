<?php

namespace App\Http\Controllers;
use App\Models\ApiBps;
use App\Models\ApiResponse;
use App\Models\DataMapping;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\FromCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PDF;
use App\Exports\ApiExport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;


class ApiBpsController extends Controller
{
    public function index(Request $request)
{
    $query = ApiBps::query();

    if ($request->has('search')) {
        $query->where('nama_instansi', 'like', '%' . $request->search . '%')
              ->orWhere('nama_data', 'like', '%' . $request->search . '%');
    }

    $apibps = $query->paginate(10);

    return view('apibps.index', compact('apibps'));
}
    
    

    public function create()
    {
        return view('apibps.create');
    }

    public function downloadTemplate()
    {
        $headers = ['Nama Instansi', 'Nama Data', 'URL API', 'Credential Key', 'ID Data', 'Method', 'Token'];
        $filename = 'template_api.xlsx';
    
        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array { return [$this->headers]; }
        }, $filename);
    }
    

    public function importtemplate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
    
        $data = Excel::toArray([], $request->file('file'))[0];
    
        $duplicates = [];
    
        foreach (array_slice($data, 1) as $row) {
            if (count($row) < 7 || empty($row[0])) { // Pastikan jumlah kolom minimal 7
                continue;
            }
    
            $existingData = ApiBps::where('nama_instansi', $row[0])
                               ->where('nama_data', $row[1])
                               ->first();
    
            if ($existingData) {
                $existingData->update([
                    'url_api' => $row[2] ?? $existingData->url_api,
                    'credential_key' => $row[3] ?? $existingData->credential_key,
                    'id_data' => $row[4] ?? $existingData->id_data,
                    'method' => $row[5] ?? $existingData->method,
                    'token' => $row[6] ?? $existingData->token, // Update token jika ada
                ]);
                $duplicates[] = [
                    'nama_instansi' => $row[0],
                    'nama_data' => $row[1]
                ];
            } else {
                ApiBps::create([
                    'nama_instansi' => $row[0] ?? null,
                    'nama_data' => $row[1] ?? null,
                    'url_api' => $row[2] ?? null,
                    'credential_key' => $row[3] ?? null,
                    'id_data' => $row[4] ?? null,
                    'method' => $row[5] ?? 'GET',
                    'token' => $row[6] ?? null, // Simpan token baru
                ]);
            }
        }
    
        if (count($duplicates) > 0) {
            $duplicateText = collect($duplicates)->map(function ($row) {
                return 'Instansi: ' . $row['nama_instansi'] . ', Data: ' . $row['nama_data'];
            })->implode(' | ');
    
            return redirect()->route('apibps.index')->with('warning', 'Data duplikat ditemukan dan telah diperbarui: ' . $duplicateText);
        }
    
        return redirect()->route('apibps.index')->with('success', 'Data berhasil diimpor.');
    }
    
        
    public function destroy($id)
    {
        $apibps = ApiBps::findOrFail($id); // Menemukan API berdasarkan ID
        $apibps->delete(); // Menghapus API dari database

        return redirect()->route('api.index')->with('success', 'Data API berhasil dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new ApiExport, 'rekap_api.xlsx');
    }

    //API BPS

public function sendRequestApi(Request $request, $apiId)
{
    // Cari API berdasarkan ID
    $apibps = ApiBps::findOrFail($apiId);

    // Ambil parameter dari request
    $parameter = $request->input('parameter', '');
    $params = [];
    
    if (!empty($parameter)) {
        parse_str($parameter, $params);
    }

    // Ambil tahun dari parameter (jika ada)
    $tahun = $params['tahun'] ?? null;

    // Siapkan URL API dan metode
    $urlApi = trim($apibps->url_api);
    $method = strtoupper($apibps->method);
    $credentialKey = $apibps->credential_key;

    // Konfigurasi request
    $options = [
        'headers' => [
            'Accept' => 'application/json',
        ],
    ];

    // Tambahkan token jika tersedia
    if (!empty($credentialKey)) {
        $options['headers']['Authorization'] = 'Bearer ' . $credentialKey;
    }

    // Tambahkan parameter ke request
    if (!empty($params)) {
        if ($method == 'GET') {
            $urlApi .= (strpos($urlApi, '?') === false ? '?' : '&') . http_build_query($params);
        } elseif ($method == 'POST') {
            // Tambahkan token ke body jika tidak ada
            if (!isset($params['token']) && !empty($credentialKey)) {
                $params['token'] = $credentialKey;
            }
            // Tambahkan tahun ke body jika belum ada
            if ($tahun && !isset($params['tahun'])) {
                $params['tahun'] = $tahun;
            }
            $options['form_params'] = $params; // Kirim data sebagai form params
        }
    }

    // Inisialisasi Guzzle Client
    $client = new Client();

    try {
        // Kirim request ke API
        $response = $client->request($method, $urlApi, $options);
        $responseData = json_decode($response->getBody(), true);

        // Cek apakah ada response lama dengan apibps_id yang sama
        $existingApiResponseQuery = ApiResponse::where('apibps_id', $apiId);

        if ($tahun) {
            $existingApiResponseQuery->whereRaw("JSON_EXTRACT(response_data, '$.tahun') = ?", [$tahun]);
        }

        $existingApiResponse = $existingApiResponseQuery->latest('version_timestamp')->first();

        if ($existingApiResponse) {
            // Tandai data lama sebagai bukan versi terbaru
            $existingApiResponse->update(['is_latest' => false]);
        }

        // Simpan data baru sebagai versi terbaru
        $newApiResponse = ApiResponse::create([
            'apibps_id' => $apiId,
            'response_data' => $responseData,
            'version_timestamp' => now(),
            'is_latest' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data versi terbaru telah disimpan.',
            'response_data' => $responseData,
        ]);

    } catch (RequestException $e) {
        return response()->json([
            'error' => 'Gagal menghubungi API',
            'message' => $e->getMessage(),
            'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 'Tidak ada status code',
            'server_response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'Tidak ada response dari server',
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Terjadi kesalahan',
            'message' => $e->getMessage(),
        ], 500);
    }
}
public function showMappingFormBps($apibps_id)
{
    // Ambil data API BPS berdasarkan id
    $apiBps = ApiBps::find($apibps_id);

    if (!$apiBps) {
        return redirect()->back()->with('error', 'API BPS tidak ditemukan.');
    }

    // Ambil respon API BPS berdasarkan apibps_id
    $apiResponse = ApiResponse::where('apibps_id', $apibps_id)->latest()->first();

    if (!$apiResponse) {
        return redirect()->back()->with('error', 'Respon API BPS tidak ditemukan.');
    }

    // Decode response_data
    $responseData = is_string($apiResponse->response_data) ? json_decode($apiResponse->response_data, true) : $apiResponse->response_data;


    if (json_last_error() !== JSON_ERROR_NONE) {
        return redirect()->back()->with('error', 'Format JSON tidak valid.');
    }

    // Ambil semua data mapping yang sudah ada
    $dataMappings = DataMapping::where('apibps_id', $apibps_id)->get();

    // Ambil field yang dibutuhkan dari response API BPS
    $sourceFields = collect(['var', 'turvar', 'vervar', 'tahun', 'turtahun']);

    return view('apibps.mapping', compact('apiBps', 'sourceFields', 'dataMappings', 'responseData'));
}

public function previewMappingBps(Request $request, $apibps_id)
{
    $apiResponse = ApiResponse::where('apibps_id', $apibps_id)->latest()->first();

    if (!$apiResponse) {
        return response()->json(['error' => 'Respon API BPS tidak ditemukan.'], 404);
    }

    $responseData = is_string($apiResponse->response_data) ? json_decode($apiResponse->response_data, true) : $apiResponse->response_data;

    if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json(['error' => 'Format JSON tidak valid.'], 400);
    }

    if (!$request->has('mapping')) {
        return response()->json(['error' => 'Mapping tidak ditemukan.'], 400);
    }

    $mappedData = [];

    foreach ($responseData as $row) {
        $mappedRow = [];

        foreach ($request->input('mapping') as $source => $target) {
            $mappedRow[$target] = $row[$source] ?? null;
        }

        if (!empty($mappedRow)) {
            $mappedData[] = $mappedRow;
        }
    }

    return response()->json(['data' => $mappedData]);
}

public function saveMappingBps(Request $request, $apibps_id)
{
    // Validasi input
    $request->validate([
        'jsonhasil' => 'required|string',
    ]);

    // Simpan ke tabel data_mappings
    DataMapping::create([
        'apibps_id' => $apibps_id,  // Jika ada relasi dengan API BPS
        'jsonhasil' => $request->input('jsonhasil'),
    ]);

    return redirect()->route('apibps.index')->with('success', 'Data berhasil disimpan ke data_mappings!');

}

public function konfirmasi($apibpsId)
    {
        // Ambil data dari tabel apibps dan data_mappings
        $apibps = ApiBps::findOrFail($apibpsId);
        $mapping = DataMapping::where('apibps_id', $apibpsId)->latest('updated_at')->first();

        // Ambil response JSON hasil mapping
        $responseData = $mapping ? json_decode($mapping->jsonhasil, true) : null;

        return view('apibps.konfirmasi', compact('apibps', 'responseData'));
    }

    public function kirimData(Request $request, $apibpsId)
{
    // Ambil data ApiBps berdasarkan ID
    $apibps = ApiBps::findOrFail($apibpsId);

    // Ambil data mapping terbaru
    $mapping = DataMapping::where('apibps_id', $apibpsId)->latest('updated_at')->first();

    // Cek apakah data mapping ditemukan
    if (!$mapping) {
        return redirect()->route('apibps.index')->with('error', 'Data mapping tidak ditemukan.');
    }

    // Data JSON yang akan dikirim
    $jsonData = json_decode($mapping->jsonhasil, true);

    // Cek apakah token ada
    $token = $apibps->token;
    if (!$token) {
        return redirect()->route('apibps.index')->with('error', 'Token tidak valid.');
    }

    // Log data JSON yang akan dikirim (untuk debugging)
    Log::info('Data JSON yang akan dikirim: ' . json_encode($jsonData));

    // Kirim request ke API tujuan
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post('https://satudata.jatengprov.go.id/v1/data', $jsonData);

    // Mengecek apakah pengiriman berhasil
    if ($response->successful()) {
        // Jika berhasil, update status atau informasi lainnya
        $apibps->status = 'Terkirim';
        $apibps->save();
        
        // Redirect dengan notifikasi sukses
        return redirect()->route('apibps.index')->with('success', 'Data berhasil dikirim!');
    }

    // Jika gagal, log status dan body respons untuk debugging
    Log::error('Pengiriman data gagal. Status: ' . $response->status() . ' - ' . $response->body());
    
    // Redirect dengan notifikasi error
    return redirect()->route('apibps.index')->with('error', 'Pengiriman data gagal! Detail: ' . $response->body());
}


    public function exportApiBps()
    {
        return Excel::download(new ApiExport, 'api_bps.xlsx');
    }

    public function exportPdfBps()
    {
        $apibps = ApiBps::all(); // Ambil data dari database
        $pdf = PDF::loadView('ApiBps.pdf', compact('apibps'));
    
        // Mengatur ukuran kertas menjadi F4 dan orientasi Landscape
        $pdf->setPaper('F4', 'landscape');
    
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="data-export.pdf"');
    }

}
