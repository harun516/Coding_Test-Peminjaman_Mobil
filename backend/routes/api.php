<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->group(function () {

    // Route untuk mendapatkan data user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [PenggunaController::class, 'logout']);
});

Route::post('/login', [PenggunaController::class, 'login'])->name('login');

Route::prefix('/pengguna')->group(function () {
    Route::post('/add', [PenggunaController::class, 'store'])->name('pengguna_add');
    Route::get('/data', [PenggunaController::class, 'index'])->name('pengguna_index');
});

Route::prefix('/mobil')->group(function () {
    Route::post('/add', [MobilController::class, 'store'])->name('mobil_add');
    Route::get('/data', [MobilController::class, 'index'])->name('mobil_index');
});

Route::prefix('/peminjaman')->group(function () {
    Route::post('/add', [CheckoutController::class, 'store'])->name('checkout_add');
    Route::get('/data', [CheckoutController::class, 'index'])->name('checkout_index');
    Route::get('/tersedia', [CheckoutController::class, 'tersedia'])->name('checkout_tersedia');
});

Route::prefix('/pengembalian')->group(function () {
    Route::post('/add', [CheckinController::class, 'store'])->name('checkin_add');
    Route::get('/data', [CheckinController::class, 'index'])->name('checkin_index');
    Route::get('/tersedia', [CheckinController::class, 'tersedia'])->name('checkin_tersedia');
});
