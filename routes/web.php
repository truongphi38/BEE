<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

// Admin controller
use App\Http\Controllers\AdminController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// admin
Route::get('/admin2/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/admin2/users', [AdminController::class, 'userlist'])->name('admin.users');
Route::get('/admin2/categories', [CategoryController::class, 'index'])->name('categories.index');


Route::post('admin2/products/store', [ProductController::class, 'store'])->name('product.store');
Route::get('admin2/product/create', [ProductController::class, 'create'])->name('product.create');
Route::delete('/admin2/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
Route::get('/admin2/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.product.edit');
Route::post('/admin2/products/update/{id}', [AdminController::class, 'updateProduct'])->name('admin.product.update');
Route::get('/admin2/products/{id}', [ProductController::class, 'show'])->name('admin.product.show');





Route::get('/admin2/category/create', [AdminController::class, 'createCategory'])->name('admin.category.create');
Route::post('/admin2/category/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
Route::delete('/admin2/category/{id}', [AdminController::class, 'destroyCategory'])->name('admin.category.destroy');
Route::get('/admin/category/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.category.edit');
Route::put('/admin/category/{id}', [AdminController::class, 'updateCategory'])->name('admin.category.update');
