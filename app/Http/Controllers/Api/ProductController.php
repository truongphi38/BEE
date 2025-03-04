<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Lấy danh sách tất cả sản phẩm
     */
    public function getProducts(): JsonResponse
    {
        $products = Product::with('type')->get([
            'id', 'name', 'img', 'description', 'price', 'discount_price',
            'status_id', 'category_id', 'type_id', 'created_at', 'updated_at'
        ]);

        return response()->json($products, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy thông tin sản phẩm theo ID
     */
    public function getProductById($id): JsonResponse
    {
        $product = Product::with('type')->find($id, [
            'id', 'name', 'img', 'description', 'price', 'discount_price',
            'status_id', 'category_id', 'type_id', 'created_at', 'updated_at'
        ]);

        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        return response()->json($product, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Thêm sản phẩm mới
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'img' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'status_id' => 'required|integer',
            'category_id' => 'nullable|integer|exists:categories,id',
            'type_id' => 'required|integer|exists:types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Sản phẩm đã được thêm!',
            'product' => $product
        ], 201);
    }

    /**
     * Cập nhật sản phẩm theo ID
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'img' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'discount_price' => 'sometimes|nullable|numeric|min:0',
            'status_id' => 'sometimes|required|integer',
            'category_id' => 'sometimes|nullable|integer|exists:categories,id',
            'type_id' => 'sometimes|required|integer|exists:types,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Sản phẩm đã được cập nhật!',
            'product' => $product
        ], 200);
    }

    /**
     * Xóa sản phẩm theo ID
     */
    public function delete($id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Sản phẩm đã bị xóa!'], 200);
    }
}
