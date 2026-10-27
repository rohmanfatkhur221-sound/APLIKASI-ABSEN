
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\MuridController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\AbsensiMuridController;
use App\Http\Controllers\Api\AbsensiGuruController;

Route::get('/status', function () {
    return response()->json([
        'success' => true,
        'message' => 'REST API Sekolah berhasil dijalankan',
        'data' => [
            'application' => 'sekolah-api',
            'version' => '1.0.0',
        ],
    ]);
});
Route::apiResource('absensi-murid', AbsensiMuridController::class)
    ->parameters(['absensi-murid' => 'absensiMurid']);

Route::apiResource('absensi-guru', AbsensiGuruController::class)
    ->parameters(['absensi-guru' => 'absensiGuru']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('gurus', GuruController::class)
    ->parameters(['gurus' => 'guru']);

Route::apiResource('kelas', KelasController::class)
    ->parameters(['kelas' => 'kelas']);

Route::apiResource('murid', MuridController::class)
    ->parameters(['murid' => 'murid']);

Route::apiResource('barang', BarangController::class)
    ->parameters(['barang' => 'barang']);
