<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function getCategories(): JsonResponse
{
    $categories = Category::all();
    return response()->json($categories, 200, [], JSON_PRETTY_PRINT);
}
}
