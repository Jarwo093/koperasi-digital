<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PinjamanController;
use App\Http\Controllers\Api\TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin,pengurus')->group(function () {
        Route::put('/pinjaman/{id}/approve', [PinjamanController::class, 'approve']);
        Route::get('/admin/pinjaman', [PinjamanController::class, 'index']);
    });

    Route::middleware('role:anggota')->group(function () {
        Route::post('/pinjaman', [PinjamanController::class, 'store']);
        Route::get('/pinjaman', [PinjamanController::class, 'index']);
        Route::put('/pinjaman/{id}/status', [PinjamanController::class, 'updateStatus']);
        Route::post('/transaksi/bayar', [TransaksiController::class, 'bayarCicilan']);
        Route::get('/transaksi/{id}/kuitansi', [TransaksiController::class, 'cetakKuitansi']);
    });
});
