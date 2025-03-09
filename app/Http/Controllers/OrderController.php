<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }


    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'status_id' => 1, // 1: Chờ xác nhận
            'item_count' => $request->item_count,
            'subtotal' => $request->subtotal,
            'promotion_id' => $request->promotion_id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Đơn hàng đã được tạo!', 'order' => $order], 201);
    }
}
