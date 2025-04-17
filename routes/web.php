<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

Route::get('/', [ProductController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [AdminAuthController::class, 'loginPage'])->name('login');

// added throttle of 5 request per minute to secure submit login route
Route::post('/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.submit');

Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

// followed laravel naming conventions for routes, added prefix
// changed named routes as per naming convention
Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('products', AdminProductController::class);
        
        // Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        // Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        // Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
        // Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        // Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
        // Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    });
