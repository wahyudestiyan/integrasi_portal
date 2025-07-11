<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataApiImport;
use Illuminate\Support\Facades\Http;
use App\Models\InstansiToken;
use App\Models\DataApi;
use App\Models\DataApiLog;
use App\Exports\DataMonitoringExport;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $instansis = InstansiToken::with('dataApis')->get();
        $selectedInstansiId = $request->input('instansi');
        $search = $request->input('search'); // Ambil input pencarian
        $selectedInstansi = null;
    
        if ($selectedInstansiId) {
            $selectedInstansi = InstansiToken::with(['dataApis' => function ($query) use ($search) {
                if ($search) {
                    $query->where('judul', 'like', '%' . $search . '%');
                }
            }])->find($selectedInstansiId);
        }
    
        return view('monitoring.index', compact('instansis', 'selectedInstansi', 'selectedInstansiId'));
    }
    

    public function updatePerInstansi($id)
{
    set_time_limit(0);
    $instansi = InstansiToken::findOrFail($id);
    $token = $instansi->bearer_token;
    $url = 'https://satudata.jatengprov.go.id/v1/data';
    $allData = [];
    $page = 1;

    do {
        $response = Http::withToken($token)->get($url, ['page' => $page]);
        if ($response->failed()) break;

        $body = $response->json();
        $allData = array_merge($allData, $body['data']);
        $meta = $body['_meta'] ?? ['pageCount' => 1];
        $page++;
    } while ($page <= $meta['pageCount']);

    foreach ($allData as $apiData) {
        $dataApi = DataApi::updateOrCreate(
            ['id_api' => $apiData['id'], 'instansi_token_id' => $instansi->id],
            ['judul' => $apiData['judul']]
        );

        $detailUrl = "https://satudata.jatengprov.go.id/v1/data/{$apiData['id']}";
        $detailPage = 1;
        $detailData = [];

        do {
            $detailResponse = Http::withToken($token)->get($detailUrl, ['page' => $detailPage]);
            if ($detailResponse->failed()) break;

            $detailJson = $detailResponse->json();
            $detailData = array_merge($detailData, $detailJson['data']);
            $detailPage++;
        } while (isset($detailJson['_meta']) && $detailPage <= $detailJson['_meta']['pageCount']);

        $tahunUnik = collect($detailData)->pluck('tahun_data')->unique()->sort()->toArray();
        $dataApi->tahun_data = implode(',', $tahunUnik);
        $dataApi->save();
    }
    
    return redirect()->back()->with('success', 'update berhasil untuk instansi: ' . $instansi->nama_instansi);
}

public function lihatLog($instansi_id)
{
    $instansi = InstansiToken::findOrFail($instansi_id);
    $token = $instansi->bearer_token;
    $url = 'https://satudata.jatengprov.go.id/v1/data';
    $allData = [];
    $page = 1;

    do {
        $response = Http::withToken($token)->get($url, ['page' => $page]);
        if ($response->failed()) break;

        $body = $response->json();
        $allData = array_merge($allData, $body['data']);
        $meta = $body['_meta'];
        $page++;
    } while ($page <= $meta['pageCount']);

    $existingData = DataApi::where('instansi_token_id', $instansi->id)->get()->keyBy('id_api');

    foreach ($allData as $apiData) {
        $id = $apiData['id'];
        $judul = $apiData['judul'];
    
        $dataApi = $existingData[$id] ?? null;
    
        // Ditambahkan
        if (!$dataApi) {
            $newDataApi = DataApi::create([
                'instansi_token_id' => $instansi->id,
                'id_api' => $id,
                'judul' => $judul,
            ]);
    
            // Tambahkan ke existingData agar bisa digunakan di loop penghapusan nanti
            $existingData->put($id, $newDataApi); // ini penting!
    
            DataApiLog::create([
                'instansi_token_id' => $instansi->id,
                'tipe_perubahan' => 'Judul Baru',
                'judul_baru' => $judul,
                'data_api_id' => $newDataApi->id, // sekarang pasti ada!
            ]);
        }
    
        // Diperbarui
        elseif ($dataApi->judul !== $judul) {
            DataApiLog::create([
                'instansi_token_id' => $instansi->id,
                'tipe_perubahan' => 'Judul Berubah',
                'judul_lama' => $dataApi->judul,
                'judul_baru' => $judul,
                'data_api_id' => $dataApi->id,
            ]);

            $dataApi->update(['judul' => $judul]);
        }
    }

    // Data dihapus
    $newIds = collect($allData)->pluck('id')->toArray();
    foreach ($existingData as $id => $data) {
        if (!in_array($id, $newIds)) {
            DataApiLog::create([
                'instansi_token_id' => $instansi->id,
                'tipe_perubahan' => 'Judul Dihapus',
                'judul_lama' => $data->judul,
                'data_api_id' => $data->id,
            ]);

            $data->delete();
        }
    }

    // ⬇️ Ganti: tampilkan log dari database, bukan perbandingan saat ini
    $logList = DataApiLog::with('dataApi')
        ->where('instansi_token_id', $instansi->id)
        ->orderByDesc('created_at')
        ->paginate(10); // 10 log per halaman  

        return view('monitoring.logs', ['logs' => $logList, 'instansi' => $instansi]);

}


public function logs(Request $request, $instansiId)
{
    $instansi = InstansiToken::findOrFail($instansiId);

    $query = $instansi->logs()->with('dataApi')->latest();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('judul_lama', 'like', "%{$search}%")
              ->orWhere('judul_baru', 'like', "%{$search}%");
        });
    }

    $logs = $query->paginate(20);

    return view('monitoring.logs', compact('instansi', 'logs'));
}


public function getDataByYear($id_api, $tahun)
{
    $dataApi = DataApi::where('id_api', $id_api)->firstOrFail();
    $instansi = $dataApi->instansi;

    if (!$instansi) {
        return response()->json(['error' => 'Instansi tidak ditemukan'], 404);
    }

    $token = $instansi->bearer_token;
    $allData = [];
    $page = 1;

    do {
        $res = Http::withToken($token)->get("https://satudata.jatengprov.go.id/v1/data/{$id_api}", ['page' => $page]);
        if ($res->failed()) break;

        $json = $res->json();
        $allData = array_merge($allData, $json['data'] ?? []);
        $page++;
    } while (isset($json['_meta']) && $page <= $json['_meta']['pageCount']);

    $availableYears = collect($allData)->pluck('tahun_data')->unique()->values();

    return response()->json([
        'requested' => $tahun,
        'judul_data' => $dataApi->judul, // ⬅️ pastikan ini ADA
        'available_years' => $availableYears,
        'data_filtered' => collect($allData)->filter(fn($item) => trim((string) $item['tahun_data']) === trim((string) $tahun))->values()
    ]);
}


public function create()
{
    return view('monitoring.create');
}

public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    Excel::import(new DataApiImport, $request->file('file'));

    return redirect()->back()->with('success', 'Data berhasil diimport dari file Excel.');
}

public function exportExcel($instansiId)
{
    return Excel::download(new DataMonitoringExport($instansiId), 'data-monitoring.xlsx');
}

}
