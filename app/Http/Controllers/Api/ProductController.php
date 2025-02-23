<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function getProducts(): JsonResponse
{
    $products = Product::all();
    return response()->json($products, 200, [], JSON_PRETTY_PRINT);
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $product = Product::create($request->all());

    return response()->json(['message' => 'Sản phẩm đã được thêm!', 'product' => $product], 201);
}
public function getProductById($id): JsonResponse
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
    }

    return response()->json($product, 200, [], JSON_PRETTY_PRINT);
}

}