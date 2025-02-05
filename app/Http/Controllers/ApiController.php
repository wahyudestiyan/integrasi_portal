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
        // Ambil data API dari database berdasarkan $apiId
        $api = Api::findOrFail($apiId);

        // Cek apakah data API dengan api_id yang sama sudah ada di ApiResponse
        $existingApiResponse = ApiResponse::where('api_id', $apiId)->first();

        if ($existingApiResponse) {
            // Jika sudah ada, kirim notifikasi bahwa data API ini sudah ada
            return response()->json([
                'success' => false,
                'message' => 'Data API ini sudah ada.',
            ], 400);  // Status code 400 untuk kesalahan permintaan
        }

        // Ambil parameter tambahan dari request form
        $parameter = $request->input('parameter');
        parse_str($parameter, $params);

        // Ambil URL API, method, dan credential key dari database
        $urlApi = trim($api->url_api); // Pastikan tidak ada spasi di URL
        $method = strtoupper($api->method); // GET atau POST
        $credentialKey = $api->credential_key;

        // Siapkan opsi untuk header dan parameter
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        // Jika ada credential key, tambahkan header Authorization
        if (!empty($credentialKey)) {
            $options['headers']['Authorization'] = 'Bearer ' . $credentialKey;
        }

        // Jika ada parameter, tambahkan ke URL atau body form
        if (!empty($params)) {
            if ($method == 'GET') {
                // Untuk GET, tambahkan parameter ke URL
                $urlApi .= (strpos($urlApi, '?') === false ? '?' : '&') . http_build_query($params);
            } else {
                // Untuk POST atau lainnya, tambahkan parameter ke body
                $options['form_params'] = $params;
            }
        }

        // Inisialisasi client Guzzle
        $client = new Client();

        try {
            // Kirim request ke API
            $response = $client->request($method, $urlApi, $options);

            // Ambil response data dan simpan ke database
            $responseData = json_decode($response->getBody(), true);

            // Simpan ke tabel ApiResponse
            $apiResponse = ApiResponse::create([
                'api_id' => $apiId,
                'response_data' => $responseData,
            ]);

            // Kembalikan response JSON dengan status sukses dan data
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'response_data' => $responseData,
            ]);

        } catch (RequestException $e) {
            // Jika terjadi error, ambil status code dan response
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'Tidak ada status code';
            $errorResponse = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'Tidak ada response dari server';

            // Cek jenis error dan kembalikan pesan yang sesuai
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
        $apiResponse = ApiResponse::where('api_id', $apiId)->first();

        if (!$apiResponse) {
            return redirect()->back()->with('error', 'API response not found');
        }

        // Ambil semua data mapping yang sudah ada
        $dataMappings = DataMapping::where('api_id', $apiId)->get();

        // Cek apakah response_data sudah dalam bentuk array atau masih string JSON
        $responseData = $apiResponse->response_data;

        if (is_string($responseData)) {
            $decodedData = json_decode($responseData, true);
        } elseif (is_array($responseData)) {
            $decodedData = $responseData;
        } else {
            return redirect()->back()->with('error', 'Invalid response data format');
        }

        // Jika decoding gagal atau tidak ada data yang bisa digunakan
        if (empty($decodedData)) {
            return redirect()->back()->with('error', 'No source fields found');
        }

        // Data untuk kolom 'source' berasal dari respon API
        $sourceFields = collect($decodedData);

        // Data target, bisa kosong dulu atau diambil dari mapping yang ada
        $targetFields = $dataMappings->pluck('target_field')->toArray();

        return view('api.mapping', compact('api', 'sourceFields', 'targetFields', 'dataMappings'));
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
        $targetFields = $request->input('target_fields'); // ['source_field1' => 'target_field1', 'source_field2' => 'target_field2']

        // Ambil response data untuk di-mapping
        $apiResponse = ApiResponse::where('api_id', $apiId)->first();
        $responseData = $apiResponse->response_data;

        // Pastikan responseData ada
        if (empty($responseData)) {
            return redirect()->back()->with('error', 'No data found to map.');
        }

        // Jika responseData adalah string JSON, decode menjadi array
        if (is_string($responseData)) {
            $responseData = json_decode($responseData, true);
        }

        // Format data sesuai dengan mapping
        $yourFormattedDataArray = [];

        foreach ($responseData as $row) {
            $mappedRow = [];

            foreach ($targetFields as $sourceField => $targetField) {
                if (isset($row[$sourceField])) {
                    $mappedRow[$targetField] = $row[$sourceField]; // Mapping data dari source ke target
                }
            }

            $yourFormattedDataArray[] = $mappedRow;
        }

        // Format JSON sesuai dengan mapping yang ada
        $formattedData = [
            "data_id" => $dataId,
            "tahun_data" => (int)date('Y') - 1,  // Tahun saat ini dikurangi 1
            "data" => $yourFormattedDataArray  // Data yang telah diformat sesuai mapping
        ];

        // Simpan hasil JSON ke dalam tabel data_mappings
        DataMapping::create([
            'api_id' => $apiId,
            'jsonhasil' => json_encode($formattedData) // Menyimpan JSON hasil mapping
        ]);

        // Menambahkan notifikasi keberhasilan
        return redirect()->back()->with('success', 'Hasil format JSON berhasil disimpan.');
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

}
