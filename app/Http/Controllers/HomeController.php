<?php
namespace App\Http\Controllers;

use App\Models\DataApi;
use App\Models\Api;
use App\Models\ApiBps;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalDataApi = DataApi::count();
        $totalApi = Api::count();
        $totalApiBps = ApiBps::count();

        $totalTerkirimApi = Api::where('status', 'terkirim')->count();
        $totalTerkirimBps = ApiBps::where('status', 'terkirim')->count();

        // Menghitung jumlah judul berdasarkan tahun_data
        $tahunCount = DataApi::all(); // Ambil semua data dari tabel DataApi
        $tahunJumlah = [];

        foreach ($tahunCount as $data) {
            // Pecah tahun_data menjadi array
            $tahunArray = explode(',', $data->tahun_data);
            
            foreach ($tahunArray as $tahun) {
                $tahun = trim($tahun); // Menghilangkan spasi tambahan

                // Tambahkan jumlah judul per tahun
                if (!isset($tahunJumlah[$tahun])) {
                    $tahunJumlah[$tahun] = 0;
                }
                $tahunJumlah[$tahun]++;
            }
        }

        // Daftar tahun yang diizinkan
        $allowedYears = [2019, 2020, 2021, 2022, 2023, 2024];

        // Filter tahun yang diizinkan
        $tahunJumlahFiltered = array_filter($tahunJumlah, function ($key) use ($allowedYears) {
            return in_array($key, $allowedYears);
        }, ARRAY_FILTER_USE_KEY);

        // Tambahkan tahun yang tidak ada datanya (0 judul)
        foreach ($allowedYears as $year) {
            if (!isset($tahunJumlahFiltered[$year])) {
                $tahunJumlahFiltered[$year] = 0; // Tahun yang tidak ada datanya di-set 0
            }
        }

        // Urutkan berdasarkan tahun
        ksort($tahunJumlahFiltered);

        return view('home', compact(
            'totalDataApi',
            'totalApi',
            'totalApiBps',
            'totalTerkirimApi',
            'totalTerkirimBps',
            'tahunJumlahFiltered'  // Menggunakan yang sudah difilter
        ));
    }
}
