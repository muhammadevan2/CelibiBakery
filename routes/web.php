<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Kasir\MenuController;
use App\Http\Controllers\KasirOrderController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| AUTH - Login & Logout
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| KASIR (Admin Panel) - Requires Auth
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('kasir')->name('kasir.')->group(function () {
    // Dashboard Kasir
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');

    // Manajemen Menu
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
        Route::post('/{menu}/status', [MenuController::class, 'updateStatus'])->name('updateStatus');
    });

    // Pemesanan oleh kasir
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [KasirOrderController::class, 'index'])->name('index'); // Halaman input order manual
        Route::post('/', [KasirOrderController::class, 'store'])->name('store');
        Route::put('/{pesanan}/status', [KasirOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{id}/confirm-payment', [KasirOrderController::class, 'confirmPayment'])->name('confirmPayment');
        Route::get('/{id}/cetak', [KasirOrderController::class, 'cetak'])->name('cetak'); // Cetak/Download struk
        Route::delete('/{pesanan}', [KasirOrderController::class, 'destroy'])->name('destroy'); // 🔥 Tambah route hapus
    });

    // Lihat semua pesanan dari pelanggan (tanpa filter login pelanggan)
    Route::get('/pesanan', [KasirOrderController::class, 'orders'])->name('orders.list');
});

/*
|--------------------------------------------------------------------------
| PELANGGAN - Akses Publik (Tanpa Login)
|--------------------------------------------------------------------------
*/
// Menu
Route::get('/', [PesananController::class, 'menu'])->name('pelanggan.menu');
Route::get('/menu', [PesananController::class, 'menu'])->name('menu');
Route::get('/menu/{id}', [PesananController::class, 'detail'])->name('pelanggan.menu.detail');

// Keranjang & Checkout
Route::post('/update-cart', [PesananController::class, 'updateCart'])->name('pelanggan.updateCart');
Route::get('/checkout', [PesananController::class, 'checkout'])->name('pelanggan.checkout');

// Kirim Pesanan
Route::post('/pesan', [PesananController::class, 'pesan'])->name('pelanggan.pesan');
