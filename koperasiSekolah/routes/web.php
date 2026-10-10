<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ProfileController;

// 1. ROUTE PUBLIK
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/explore', [CatalogController::class, 'index'])->name('explore');

// 2. AUTH ROUTES
require __DIR__.'/auth.php';

// 3. ROUTE TERPROTEKSI (Harus Login)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // KERANJANG BELANJA (CART)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // RIWAYAT BELANJA
    Route::get('/history', [TransactionController::class, 'userHistory'])->name('history.index');

    // GROUP ADMIN
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['admin'])
        ->group(function () {
            Route::get('/dashboard', [InventoryController::class, 'index'])->name('dashboard');
            Route::resource('inventory', InventoryController::class);
            Route::resource('categories', CategoryController::class);
            Route::get('reports', [TransactionController::class, 'report'])->name('reports');
            Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
        });
});