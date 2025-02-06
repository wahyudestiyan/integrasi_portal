<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


// Route default diarahkan ke API Index
Route::get('/', [ApiController::class, 'index'])->name('api.index');

//route download excel dan upload
Route::get('/apis/download-template', [ApiController::class, 'downloadTemplate'])->name('apis.download-template');
Route::post('/apis/import', [ApiController::class, 'import'])->name('apis.import');

// Route lainnya
Route::get('/api/create', [ApiController::class, 'create'])->name('api.create');
Route::post('/api', [ApiController::class, 'store'])->name('api.store');
Route::post('/api/{api}/send', [ApiController::class, 'send'])->name('api.send');
Route::delete('apis/{id}', [ApiController::class, 'destroy'])->name('apis.destroy');
// Route::get('apis/{api}', [ApiController::class, 'show'])->name('apis.show');

Route::post('/api/{apiId}/send-request', [ApiController::class, 'sendRequest'])->name('api.send_request');
// Route::get('/apis/{apiId}', [ApiController::class, 'showApiDetails'])->name('api.show');

// Route::post('/api/{apiId}/save-response', [ApiController::class, 'saveResponse'])->name('api.save_response');

// Route::post('/api/{api}/send-request', [ApiController::class, 'sendRequestAndSaveResponse'])->name('api.send_request');

Route::get('/api/{apiId}/mapping', [ApiController::class, 'showMappingForm'])->name('api.mapping');
Route::post('/api/{apiId}/mapping', [ApiController::class, 'saveMapping'])->name('api.mapping.save');
Route::get('/konfirmasi/{apiId}', [ApiController::class, 'konfirmasi'])->name('api.konfirm');
Route::post('/api/kirim/{apiId}', [ApiController::class, 'kirimData'])->name('api.kirim');






// Route::get('/api/api-konfirm/{id}', [ApiController::class, 'apiKonfirm'])->name('api.konfirm');
// Route::post('/api/cek-konfirm/{id}', [ApiController::class, 'cekKonfirm'])->name('cek.konfirm');
// Route::get('api/tujuan', [ApiController::class, 'tujuan'])->name('api.tujuan');

// Route::get('/api/{apiId}/mapping', [ApiController::class, 'showMapping'])->name('api.mapping');
// Route::post('/save-mapping', [ApiController::class, 'saveMapping'])->name('api.saveMapping');

// Route::get('/test-api', [App\Http\Controllers\ApiController::class, 'testApi']);
