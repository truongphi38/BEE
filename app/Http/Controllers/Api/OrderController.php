<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
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
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric',
            'status_id' => 'required|integer',
            //'item_count' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'promotion_id' => 'nullable|integer|exists:promotions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create($request->all());

        return response()->json([
            'message' => 'Đơn hàng đã được tạo thành công!',
            'order' => $order
        ], 201);
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
