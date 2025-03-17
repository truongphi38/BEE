<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    // Lấy danh sách đơn hàng
    public function index(): JsonResponse
    {
        $orders = Order::all();
        return response()->json($orders, 200, [], JSON_PRETTY_PRINT);
    }

    // Lấy thông tin đơn hàng theo ID
    public function show($id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }
        return response()->json($order, 200, [], JSON_PRETTY_PRINT);
    }

    // Lấy danh sách đơn hàng theo user_id
    public function getOrdersByUser($user_id): JsonResponse
    {
        $orders = Order::where('user_id', $user_id)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'Không có đơn hàng nào cho user này'], 404);
        }

        return response()->json($orders, 200, [], JSON_PRETTY_PRINT);
    }


    // Tạo đơn hàng mới
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.productvariant_id' => 'required|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Tạo đơn hàng
            $order = Order::create([
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'status_id' => 1, // 1: Chờ xác nhận
                'subtotal' => $request->subtotal,
                'promotion_id' => $request->promotion_id ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Lưu chi tiết từng sản phẩm vào order_details
            foreach ($request->products as $product) {
                $variant = ProductVariant::find($product['productvariant_id']);

                if (!$variant || $variant->stock_quantity < $product['quantity']) {
                    return response()->json(['message' => 'Sản phẩm không đủ hàng'], 400);
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'productvariant_id' => $product['productvariant_id'],
                    'quantity' => $product['quantity'],
                    'total_price' => $variant->price * $product['quantity']
                ]);

                // 3. Trừ kho hàng
                $variant->decrement('stock_quantity', $product['quantity']);
            }

            DB::commit();
            return response()->json(['message' => 'Đơn hàng đã được tạo thành công!', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo đơn hàng!'], 500);
        }
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|integer|exists:users,id',
            'total_amount' => 'sometimes|required|numeric',
            'status_id' => 'sometimes|required|integer',
            //'item_count' => 'sometimes|required|integer|min:1',
            'subtotal' => 'sometimes|required|numeric',
            'promotion_id' => 'nullable|integer|exists:promotions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->all());

        return response()->json([
            'message' => 'Cập nhật đơn hàng thành công!',
            'order' => $order
        ], 200);
    }

    // Xóa đơn hàng
    public function destroy($id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Xóa đơn hàng thành công!'], 200);
    }
}
