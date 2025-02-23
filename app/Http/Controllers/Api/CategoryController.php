<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function getCategories(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories, 200, [], JSON_PRETTY_PRINT);
    }

    public function getCategoryById($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        return response()->json($category, 200, [], JSON_PRETTY_PRINT);
    }

    public function getProductsByCategoryId($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tồn tại'
            ], 404);
        }

        $products = Product::where('category_id', $id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách sản phẩm theo danh mục thành công',
            'data' => $products
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
