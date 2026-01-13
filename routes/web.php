<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AdminProductController; // Controller import

// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/products/create/step1', [AdminProductController::class, 'step1'])->name('products.create.step1');
//     Route::post('/products/create/step1', [AdminProductController::class, 'postStep1'])->name('products.create.post1');
//     Route::get('/products/create/step2', [AdminProductController::class, 'step2'])->name('products.create.step2');
//     Route::post('/products/create/step2', [AdminProductController::class, 'postStep2'])->name('products.create.post2');
//     Route::get('/products/create/step3', [AdminProductController::class, 'step3'])->name('products.create.step3');
//     Route::post('/products/create/step3', [AdminProductController::class, 'postStep3'])->name('products.create.post3');
// });
Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');
Route::get('/admin/products/create/step1', [AdminProductController::class, 'step1'])->name('admin.products.create.step1');
Route::post('/admin/products/create/step1', [AdminProductController::class, 'postStep1'])->name('admin.products.create.post1');
Route::get('/admin/products/create/step2', [AdminProductController::class, 'step2'])->name('admin.products.create.step2');
Route::post('/admin/products/create/step2', [AdminProductController::class, 'postStep2'])->name('admin.products.create.post2');
Route::get('/admin/products/create/step3', [AdminProductController::class, 'step3'])->name('admin.products.create.step3');
Route::post('/admin/products/create/step3', [AdminProductController::class, 'postStep3'])->name('admin.products.create.post3');

// use App\Http\Controllers\AdminProductController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Tumhare existing create routes (step1, step2, step3) yahan hain...
    
    // NEW: Yeh add karo – Products index route (list dikhane ke liye)
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
});