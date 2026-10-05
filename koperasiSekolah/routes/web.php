<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN IMPORT FACADE INI BENAR
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ProfileController;

// ==========================================
// 1. ROUTE PUBLIK (Guest / Pengunjung)
// ==========================================
Route::get('/', [CatalogController::class, 'index'])->name('home');

// ==========================================
// 2. AUTH ROUTES (Laravel Breeze)
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// 3. ROUTE TERPROTEKSI (Harus Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    /**
     * Dashboard redirect dengan Facade Auth yang aman dari error metode
     */
    Route::get('/dashboard', function () {
        // Mengambil data user yang sedang login secara eksplisit
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('home'); 
    })->name('dashboard');

    /**
     * Rute Profil User
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * GROUP KHUSUS ADMIN
     */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['admin']) 
        ->group(function () {

            Route::get('/dashboard', [InventoryController::class, 'index'])->name('dashboard');
            Route::resource('inventory', InventoryController::class);
            Route::get('reports', [TransactionController::class, 'report'])->name('reports');
        });

    /**
     * ROUTE USER / SISWA / ORANG TUA
     */
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
});