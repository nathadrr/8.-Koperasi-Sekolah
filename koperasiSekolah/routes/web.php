<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('login');
});


// 1. ROUTE PUBLIK (Guest)
// Halaman utama langsung diarahkan ke katalog agar user-friendly
Route::get('/', [CatalogController::class, 'index'])->name('home');


// 2. AUTH ROUTES (Laravel Breeze)
// Breeze menyimpan route login, register, password reset di file auth.php
require __DIR__.'/auth.php';


// 3. ROUTE TERPROTEKSI (Harus Login)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama setelah login
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // GROUP KHUSUS ADMIN
    // prefix('admin') -> url menjadi /admin/...
    // name('admin.') -> route menjadi admin.inventory.index, dll.
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard khusus admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Resource untuk Inventory (index, create, store, edit, update, destroy)
        Route::resource('inventory', InventoryController::class);
        
        // Laporan Penjualan
        Route::get('reports', [TransactionController::class, 'report'])->name('reports');
    });

    // ROUTE USER / SISWA / ORANG TUA
    // Proses pembayaran/checkout
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
});
