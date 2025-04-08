<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VisualisasiController;
use App\Http\Controllers\ApiBpsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StatisBpsController;
use App\Http\Controllers\MonitoringController;



Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/api', [ApiController::class, 'index'])->name('api.index')->middleware('auth');


//route download excel dan upload
Route::get('/apis/download-template', [ApiController::class, 'downloadTemplate'])->name('apis.download-template');
Route::post('/apis/import', [ApiController::class, 'import'])->name('apis.import');

// Route lainnya
Route::get('/api/create', [ApiController::class, 'create'])->name('api.create');
Route::post('/api', [ApiController::class, 'store'])->name('api.store');
Route::post('/api/{api}/send', [ApiController::class, 'send'])->name('api.send');
Route::delete('apis/{id}', [ApiController::class, 'destroy'])->name('apis.destroy');

Route::post('/api/{apiId}/send-request', [ApiController::class, 'sendRequest'])->name('api.send_request');


Route::get('/api/{apiId}/mapping', [ApiController::class, 'showMappingForm'])->name('api.mapping');
Route::post('/api/{apiId}/mapping', [ApiController::class, 'saveMapping'])->name('api.mapping.save');
Route::get('/konfirmasi/{apiId}', [ApiController::class, 'konfirmasi'])->name('api.konfirm');
Route::post('/api/kirim/{apiId}', [ApiController::class, 'kirimData'])->name('api.kirim');


Route::get('/api/export-excel', [ApiController::class, 'exportExcel'])->name('api.export-excel');
Route::get('/api/export-pdf', [ApiController::class, 'exportPdf'])->name('api.export-pdf');
Route::get('/export-mapping/{apiId}', [ApiController::class, 'exportToExcel'])->name('export.mapping');


// visualisasi

Route::middleware(['auth'])->group(function () {
    Route::get('/visualisasi/create', [VisualisasiController::class, 'create'])->name('visualisasi.create');
    Route::post('/visualisasi/import', [VisualisasiController::class, 'import'])->name('visualisasi.import');
    Route::get('/visualisasi/download-template', [VisualisasiController::class, 'downloadTemplate'])->name('visualisasi.download-template');
});

Route::get('/visualisasi/create', [VisualisasiController::class, 'create'])->name('visualisasi.create');
Route::post('/visualisasi/import', [VisualisasiController::class, 'import'])->name('visualisasi.import');

Route::get('/visualisasi', [VisualisasiController::class, 'index'])->name('visualisasi.index');
Route::get('/visualisasi/{instansiId}/data', [VisualisasiController::class, 'getDataInstansi']);
// Route::get('/visualisasi/{instansiId}/data/{dataId}', [VisualisasiController::class, 'getDetailData']);
Route::get('/visualisasi/{instansiId}/data/{dataId}/detail', [VisualisasiController::class, 'show'])->name('visualisasi.detail');
Route::get('/visualisasi/{instansiId}/data/{dataId}/tabulasi', [VisualisasiController::class, 'tabulasi'])->name('visualisasi.tabulasi');

Route::post('/tabulasi', [VisualisasiController::class, 'tabulasi'])->name('tabulasi');


//ROUTE FUNGSI BPS API
Route::get('/apibps', [ApiBpsController::class, 'index'])->name('apibps.index')->middleware('auth');
//route download excel dan upload
Route::get('/apibps/download-template', [ApiBpsController::class, 'downloadTemplate'])->name('apibps.download-template');
Route::post('/apibps/import', [ApiBpsController::class, 'importTemplate'])->name('apibps.import');
Route::post('/apibps/{apiId}/send-request', [ApiBpsController::class, 'sendRequestApi'])->name('apibps.send_request');


Route::prefix('apibps')->group(function () {
    Route::get('/mapping/{apibps_id}', [ApiBpsController::class, 'showMappingFormBps'])->name('apibps.mappingForm');
    Route::post('/preview-mapping/{apibps_id}', [ApiBpsController::class, 'previewMappingBps'])->name('apibps.previewMapping');
    Route::post('/save-mapping/{apibps_id}', [ApiBpsController::class, 'saveMappingBps'])->name('apibps.saveMapping');
    Route::get('/{apibpsId}/konfirmasi', [ApiBpsController::class, 'konfirmasi'])->name('apibps.konfirmasi');
Route::post('/{apibpsId}/kirim', [ApiBpsController::class, 'kirimData'])->name('apibps.kirim');
Route::get('/export-api-bps', [ApiBpsController::class, 'exportApiBps'])->name('export.api.bps');
Route::get('/export-pdf', [ApiBpsController::class, 'exportPdfBps'])->name('apibps.export-pdf');
Route::delete('/del/{id}', [ApiBpsController::class, 'destroy'])->name('apibps.destroy');

});


Route::get('/statisbps', [StatisBpsController::class, 'index'])->name('statisbps.index');
Route::post('statisbps', [StatisBpsController::class, 'store'])->name('statisbps.store');
Route::post('statisbps', [StatisBpsController::class, 'create'])->name('statisbps.create');
Route::post('statisbps/mapping', [StatisBpsController::class, 'processMapping'])->name('statisbps.processMapping');
Route::delete('/statisbps/{id}', [StatisBpsController::class, 'destroy'])->name('statisbps.destroy');
Route::get('/statisbps/{id}/lihat-csv', [StatisBpsController::class, 'lihatCsvData']);
Route::resource('statisbps', \App\Http\Controllers\StatisBPSController::class);
Route::get('/statisbps/{id}/mapping', [StatisBpsController::class, 'show'])->name('statisbps.mapping');
Route::post('/statisbps/mapping/process', [StatisBpsController::class, 'processMapping'])->name('statisbps.mapping.process');



Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
Route::post('/monitoring/update', [MonitoringController::class, 'update'])->name('monitoring.update');
Route::get('/monitoring/{instansi}/logs', [MonitoringController::class, 'lihatLog'])->name('monitoring.logs');
Route::get('/monitoring/logs/{instansiId}', [MonitoringController::class, 'logs'])->name('monitoring.logs');





// Route lainnya
Route::get('/apibps/create', [ApiBpsController::class, 'create'])->name('apibps.create');


