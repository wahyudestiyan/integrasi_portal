<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
         // Panggil API dari portal data
    $response = Http::get('https://satudata.jatengprov.go.id/v1/data');
    $data = $response->json();

    // Pastikan data yang diterima adalah array
    if (!is_array($data)) {
        $data = [];
    }

    // Hitung total judul data
    $totalJudul = count($data);

    // Hitung jumlah data berdasarkan tahun_data
    $dataPerTahun = [];
    foreach ($data as $item) {
        $tahun = $item['tahun_data'] ?? 'Unknown';
        if (!isset($dataPerTahun[$tahun])) {
            $dataPerTahun[$tahun] = 0;
        }
        $dataPerTahun[$tahun]++;
    }

    return view('home', compact('totalJudul', 'dataPerTahun'));
    }
}
