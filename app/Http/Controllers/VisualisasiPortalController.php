<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisualisasiPortalController extends Controller
{
    /**
     * Tampilkan halaman utama visualisasi portal.
     */
    public function index()
    {
        // Data bisa diambil dari model jika diperlukan
        // Contoh: $data = Visualisasi::all();

        return view('visualisasiportal.index');
 // Mengarah ke resources/views/visualisasi/index.blade.php
    }

    /**
     * Menampilkan detail visualisasi tertentu.
     */
}
