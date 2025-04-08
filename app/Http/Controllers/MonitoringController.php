<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\InstansiToken;
use App\Models\DataApi;
use App\Models\DataApiLog;

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
    


public function update(Request $request)
{
    set_time_limit(0);
    $instansis = InstansiToken::all();

    foreach ($instansis as $instansi) {
        $token = $instansi->bearer_token;
        $url = 'https://satudata.jatengprov.go.id/v1/data';
        $allData = [];
        $page = 1;

        // Ambil semua judul data
        do {
            $response = Http::withToken($token)->get($url, ['page' => $page]);
            if ($response->failed()) break;

            $body = $response->json();
            $allData = array_merge($allData, $body['data']);
            $meta = $body['_meta'];
            $page++;
        } while ($page <= $meta['pageCount']);

        // Proses masing-masing data judul
        foreach ($allData as $apiData) {
            // Simpan atau update judul API
            $dataApi = DataApi::updateOrCreate(
                ['id_api' => $apiData['id'], 'instansi_token_id' => $instansi->id],
                ['judul' => $apiData['judul']]
            );

            // Ambil data detail berdasarkan ID
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

            // Ambil tahun-tahun unik dari data detail
            $tahunUnik = collect($detailData)->pluck('tahun_data')->unique()->sort()->toArray();

            // Simpan ke kolom tahun_data
            $dataApi->tahun_data = implode(',', $tahunUnik);
            $dataApi->save();
        }
    }

    return redirect()->route('monitoring.index')->with('success', 'Data berhasil disinkronkan.');
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
        ->get();

    return view('monitoring.logs', compact('logList', 'instansi'));
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


}
