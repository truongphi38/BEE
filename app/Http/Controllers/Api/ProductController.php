<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Lấy danh sách tất cả sản phẩm
     */
    public function getProducts(): JsonResponse
    {
        $products = Product::with(['category', 'type', 'productVariants.color'])
            ->select('id', 'name', 'img', 'description', 'price', 'discount_price', 'category_id', 
                    'type_id', 'created_at', 'updated_at', 'purchase_count','is_hot')
            ->get();
        return response()->json($products, 200);
    }

    /**
     * Lấy danh sách sản phẩm hot
     */
    public function getHotProducts(): JsonResponse
    {
        $products = Product::with(['category', 'type', 'productVariants.color'])
            ->where('is_hot', 1)
            ->get();

        return response()->json($products, 200);
    }

    /**
     * Lấy thông tin sản phẩm theo ID
     */
    public function getProductById($id): JsonResponse
    {
        $product = Product::with(['category', 'type', 'productVariants.color'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        return response()->json($product, 200);
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
            'category_id' => 'nullable|integer|exists:categories,id',
            'type_id' => 'required|integer|exists:types,id',
            'variants' => 'nullable|array',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::create($request->only([
                'name', 'img', 'description', 'price', 'discount_price', 'category_id', 'type_id'
            ]));

            if ($request->has('variants')) {
                foreach ($request->variants as $variant) {
                    $product->productVariants()->create($variant);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Sản phẩm đã được thêm!',
                'product' => $product->load('productVariants.color')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi thêm sản phẩm: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi khi thêm sản phẩm'], 500);
        }
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
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        if (method_exists($product, 'orders') && $product->orders()->exists()) {
            return response()->json(['message' => 'Không thể xóa sản phẩm vì đã có đơn hàng liên quan'], 400);
        }

        $product->delete();

        return response()->json(['message' => 'Sản phẩm đã bị xóa!'], 200);
    }

    /**
     * Tìm kiếm sản phẩm theo tên hoặc mô tả
     */
    public function search($query)
    {
        $products = Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        return response()->json($products, 200);
    }
}
