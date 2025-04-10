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

    return view('home', compact(
        'totalDataApi', 
        'totalApi', 
        'totalApiBps', 
        'totalTerkirimApi', 
        'totalTerkirimBps'
    ));
}
}
