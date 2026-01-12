<?php
// header('Access-Control-Allow-Origin: *'); 
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); 
// header('Access-Control-Allow-Headers: Content-Type, Authorization');


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

// Public Routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);

// Admin Routes (Protected)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('sub_categories', SubCategoryController::class);
    Route::apiResource('products', ProductController::class);
});