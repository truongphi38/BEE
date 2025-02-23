<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

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
//Route::get('/products', [ProductController::class, 'index']);
Route::get('products', [ProductController::class, 'getProducts']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/products/{id}', [ProductController::class, 'getProductById']);
Route::get('/categories/{id}', [CategoryController::class, 'getCategoryById']);
Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategoryId']);
    
