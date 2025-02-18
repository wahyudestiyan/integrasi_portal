<?php

namespace App\Http\Controllers;
use App\Models\Api;
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


class ApiController extends Controller
{
        public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 5 data per halaman

        $apis = Api::when($search, function ($query, $search) {
            $query->where('nama_instansi', 'like', '%' . $search . '%')
                ->orWhere('nama_data', 'like', '%' . $search . '%');
        })->paginate($perPage); // Ganti simplePaginate dengan paginate

        return view('api.index', compact('apis', 'search', 'perPage'));
    }

    public function sendRequest(Request $request, $apiId)
{
    // Ambil data API dari database
    $api = Api::findOrFail($apiId);

    // Ambil parameter dari request
    $parameter = $request->input('parameter');
    parse_str($parameter, $params);
    $tahun = $params['tahun'] ?? null; // Cek apakah ada parameter tahun

    // Ambil URL API, method, dan credential key
    $urlApi = trim($api->url_api);
    $method = strtoupper($api->method);
    $credentialKey = $api->credential_key;

    // Konfigurasi request API
    $options = [
        'headers' => [
            'Accept' => 'application/json',
        ],
    ];

    // Menambahkan token ke header jika ada
    if (!empty($credentialKey)) {
        $options['headers']['Authorization'] = 'Bearer ' . $credentialKey;
    }

    // Menambahkan parameter dan token ke body request jika metode adalah POST
    if (!empty($params)) {
        if ($method == 'GET') {
            // Menambahkan parameter ke query string untuk GET
            $urlApi .= (strpos($urlApi, '?') === false ? '?' : '&') . http_build_query($params);
        } elseif ($method == 'POST') {
            // Menambahkan token ke dalam body jika POST dan key 'token' belum ada
            if (!isset($params['token']) && !empty($credentialKey)) {
                $params['token'] = $credentialKey; // Menambahkan token ke body
            }
            // Menambahkan tahun ke dalam body jika ada
            if ($tahun && !isset($params['tahun'])) {
                $params['tahun'] = $tahun; // Menambahkan tahun ke body jika belum ada
            }
            // Masukkan semua parameter ke dalam form_params untuk body POST
            $options['form_params'] = $params; // Untuk x-www-form-urlencoded
        }
    }

    // Inisialisasi Guzzle Client
    $client = new Client();

    try {
        // Kirim request ke API
        $response = $client->request($method, $urlApi, $options);
        $responseData = json_decode($response->getBody(), true);

        // Cek apakah ada data lama dengan api_id yang sama
        $existingApiResponse = ApiResponse::where('api_id', $apiId);

        if ($tahun) {
            $existingApiResponse = $existingApiResponse->whereJsonContains('response_data->tahun', (string) $tahun);
        }

        $existingApiResponse = $existingApiResponse->latest('version_timestamp')->first();

        if ($existingApiResponse) {
            // Tandai data lama sebagai bukan versi terbaru
            $existingApiResponse->update(['is_latest' => false]);
        }

        // Simpan data baru sebagai versi terbaru
        $newApiResponse = ApiResponse::create([
            'api_id' => $apiId,
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
        $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'Tidak ada status code';
        $errorResponse = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'Tidak ada response dari server';

        return response()->json([
            'error' => 'Gagal menghubungi API',
            'message' => $e->getMessage(),
            'status_code' => $statusCode,
            'server_response' => $errorResponse
        ], 500);
    }
}

    
   public function showMappingForm($apiId)
{
    // Ambil data API berdasarkan id
    $api = Api::find($apiId);

    if (!$api) {
        return redirect()->back()->with('error', 'API not found');
    }

    // Ambil respon data dari ApiResponse berdasarkan api_id
    $apiResponse = ApiResponse::where('api_id', $apiId)->latestVersion()->first();

    if (!$apiResponse) {
        return redirect()->back()->with('error', 'API response not found');
    }

    // Ambil semua data mapping yang sudah ada
    $dataMappings = DataMapping::where('api_id', $apiId)->get();

    // Ambil response data
    $responseData = $apiResponse->response_data;

    // Memeriksa apakah data sudah berupa JSON string dan decode jika perlu
    if (is_string($responseData)) {
        $decodedData = json_decode($responseData, true);

        // Cek jika decoding JSON gagal
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Invalid JSON format in Source Fields');
        }
    } elseif (is_array($responseData)) {
        // Jika sudah dalam format array, langsung diproses
        $decodedData = $responseData;
    } else {
        return redirect()->back()->with('error', 'Invalid response data format');
    }

    // Fungsi untuk menelusuri data respons secara rekursif
    $dynamicFields = $this->findArrayKeys($decodedData);

    if (empty($dynamicFields)) {
        return redirect()->back()->with('error', 'No dynamic fields found in the API response');
    }

    // Data untuk kolom 'source' berasal dari dynamic fields yang ditemukan
    $sourceFields = collect($dynamicFields);

    // Data target, bisa kosong dulu atau diambil dari mapping yang ada
    $targetFields = $dataMappings->pluck('target_field')->toArray();

    return view('api.mapping', compact('api', 'sourceFields', 'targetFields', 'dataMappings'));
}

/**
 * Fungsi rekursif untuk menelusuri array atau objek dan mencari kunci yang berisi array.
 */
private function findArrayKeys($data)
{
    $result = [];

    // Jika data adalah array
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            // Jika value adalah array, simpan kunci dan data tersebut
            if (is_array($value)) {
                $result[$key] = $value;
            }

            // Jika value adalah objek atau array dalam objek, lakukan rekursi
            if (is_array($value) || is_object($value)) {
                $result = array_merge($result, $this->findArrayKeys($value));
            }
        }
    }

    // Jika data adalah objek, lakukan rekursi juga
    if (is_object($data)) {
        foreach ($data as $key => $value) {
            $result = array_merge($result, $this->findArrayKeys($value));
        }
    }

    return $result;
}

    

public function saveMapping(Request $request, $apiId)
{
    // Mengambil data dari tabel apis
    $api = Api::find($apiId);
    if (!$api) {
        return redirect()->back()->with('error', 'API not found');
    }

    $token = $api->token;  // Token Bearer
    $dataId = $api->id_data; // data_id

    // Mengambil data mapping dari request
    $targetFields = $request->input('target_fields'); // ['source_field1' => 'target_field1', ...]
    // Mengambil tahun_data dari input pengguna, jika tidak ada gunakan default
    $tahunData = $request->input('tahun_data');
    
            // Ambil data dari request
        $targetFieldsOrder = $request->input('target_fields_order');

        // Validasi apakah targetFieldsOrder berbentuk array
        if (empty($targetFieldsOrder) || !is_array(json_decode($targetFieldsOrder, true))) {
            return redirect()->back()->with('error', 'Target fields order should be an array.');
        }

        // Dekode JSON jika perlu
        $targetFieldsOrder = json_decode($targetFieldsOrder, true);
        
    // Ambil response data untuk di-mapping
    $apiResponse = ApiResponse::where('api_id', $apiId)->latestVersion()->first();

    if (!$apiResponse) {
        return redirect()->back()->with('error', 'No API response found.');
    }

    // Cek apakah response_data sudah array atau masih string JSON
    $responseData = is_array($apiResponse->response_data) 
        ? $apiResponse->response_data 
        : json_decode($apiResponse->response_data, true);

    if (!$responseData) {
        return redirect()->back()->with('error', 'Invalid JSON response.');
    }

    // Cari array utama dalam responseData
    $mainDataArray = $this->findMainDataArray($responseData);

    if (!$mainDataArray || !is_array($mainDataArray)) {
        return redirect()->back()->with('error', 'No valid data array found.');
    }

    // Debugging log untuk melihat data
    \Log::debug('targetFieldsOrder:', $targetFieldsOrder);
    \Log::debug('mainDataArray:', $mainDataArray);
    \Log::debug('targetFields:', $targetFields);

    // Mapping data sesuai dengan targetFields
    $yourFormattedDataArray = [];

    foreach ($mainDataArray as $row) {
        $mappedRow = [];

        foreach ($targetFieldsOrder as $field) {
            if (isset($targetFields[$field])) {
                $mappedRow[$targetFields[$field]] = $this->getNestedValue($row, $field);
            }
        }

        $yourFormattedDataArray[] = $mappedRow;
    }

    // Format JSON sesuai dengan kebutuhan
    $formattedData = [
        "data_id" => $dataId,
        "tahun_data" => (int) $tahunData,
        "data" => $yourFormattedDataArray
    ];

    // Simpan hasil JSON ke dalam tabel data_mappings
    DataMapping::create([
        'api_id' => $apiId,
        'jsonhasil' => json_encode($formattedData)
    ]);

    return redirect()->back()->with('success', 'Hasil format JSON berhasil disimpan.');
}


/**
 * Fungsi untuk menemukan array utama dalam response API secara fleksibel
 */
private function findMainDataArray($data)
{
    if (!is_array($data)) {
        return null;
    }

    // Jika ini adalah array indeks, maka ini adalah array utama yang kita cari
    if (!$this->isAssociativeArray($data)) {
        return $data;
    }

    // Lakukan pencarian dalam setiap elemen array
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $result = $this->findMainDataArray($value);
            if ($result !== null) {
                return $result;
            }
        }
    }

    return null;
}

/**
 * Fungsi untuk mengecek apakah array adalah asosiatif atau indeks
 */
private function isAssociativeArray($array)
{
    return (array_keys($array) !== range(0, count($array) - 1));
}

/**
 * Fungsi untuk mendapatkan nilai dari array nested (bertingkat)
 */
private function getNestedValue($array, $key, $default = null)
{
    if (isset($array[$key])) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (is_array($array) && isset($array[$segment])) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }

    return $array;
}



    public function konfirmasi($apiId)
    {
        // Ambil data dari tabel apis dan data_mappings
        $api = Api::findOrFail($apiId);
        $mapping = DataMapping::where('api_id', $apiId)->latest('updated_at')->first(); 
    
        // Pastikan response_data ada
        $responseData = $mapping ? $mapping->jsonhasil : null;
    
        // Jika jsonhasil berupa string JSON, decode menjadi array
        if ($responseData) {
            $responseData = json_decode($responseData, true);
        }
        // dd($responseData);

    
        return view('api.konfirm', compact('api', 'responseData'));
    }
    


    public function kirimData(Request $request, $apiId)
{
    $api = Api::findOrFail($apiId);
    
    // Ambil data mapping terbaru untuk api_id yang diupdate terakhir
    $mapping = DataMapping::where('api_id', $apiId)->latest('updated_at')->first();  // Ambil data mapping terbaru
    
    // Pastikan ada data mapping yang ditemukan
    if (!$mapping) {
        return redirect()->route('api.index')->with('error', 'Data mapping tidak ditemukan.');
    }
    
    // Siapkan data yang akan dikirim, misalnya data yang sudah dipetakan
    $jsonData = json_decode($mapping->jsonhasil, true); // Mengambil data yang sudah dipetakan dari model 'DataMapping'
    
    // Token untuk Authorization
    $token = $api->token;  // Mengambil token dari field 'token'

    // Kirim request ke API tujuan
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post('https://satudata.jatengprov.go.id/v1/data', $jsonData);

    // Mengecek apakah pengiriman berhasil
    if ($response->successful()) {
        // Jika berhasil, update status atau informasi lainnya
        $api->status = 'Terkirim';
        $api->save();

        return redirect()->route('api.index')->with('success', 'Data berhasil dikirim!');
    }

    // Jika gagal, kembalikan error
    return redirect()->route('api.index')->with('error', 'Pengiriman data gagal!');
}

        
    public function create()
    {
        return view('api.create');
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
    

    public function import(Request $request)
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
    
            $existingData = Api::where('nama_instansi', $row[0])
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
                Api::create([
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
    
            return redirect()->route('api.index')->with('warning', 'Data duplikat ditemukan dan telah diperbarui: ' . $duplicateText);
        }
    
        return redirect()->route('api.index')->with('success', 'Data berhasil diimpor.');
    }
    
        
    public function destroy($id)
    {
        $api = Api::findOrFail($id); // Menemukan API berdasarkan ID
        $api->delete(); // Menghapus API dari database

        return redirect()->route('api.index')->with('success', 'Data API berhasil dihapus.');
    }

    public function exportExcel()
    {
        return Excel::download(new ApiExport, 'rekap_api.xlsx');
    }

    // Fungsi untuk export PDF
    public function exportPDF()
    {
        $apis = Api::all(); // Ambil data dari database
        $pdf = PDF::loadView('api.pdf', compact('apis'));
    
        // Mengatur ukuran kertas menjadi F4 dan orientasi Landscape
        $pdf->setPaper('F4', 'landscape');
    
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="data-export.pdf"');
    }
}
