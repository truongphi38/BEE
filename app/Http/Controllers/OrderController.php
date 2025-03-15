<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function index2()
    {
        $orders = Order::where('status_id', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index2', compact('orders'));
    }
    public function index3()
    {
        $orders = Order::where('status_id', 2)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index3', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderDetails.productVariant'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    



    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'subtotal' => 'required|numeric',
        'total_amount' => 'required|numeric',
        'promotion_id' => 'nullable|exists:promotions,id',
        'products' => 'required|array',
        'products.*.product_variant_id' => 'required|exists:product_variants,id',
        'products.*.quantity' => 'required|integer|min:1'
    ]);

    // Tạo đơn hàng
    $order = Order::create([
        'user_id' => $request->user_id,
        'total_amount' => $request->total_amount,
        'subtotal' => $request->subtotal,
        'promotion_id' => $request->promotion_id ?? null,
        'status_id' => 1, // Chờ xác nhận
    ]);

    // Thêm chi tiết đơn hàng
    foreach ($request->products as $product) {
        $order->orderDetails()->create([
            'product_variant_id' => $product['product_variant_id'],
            'quantity' => $product['quantity'],
            'price' => ProductVariant::find($product['product_variant_id'])->price, // Lấy giá từ DB
        ]);
    }

    return response()->json(['message' => 'Đơn hàng đã được tạo!', 'order' => $order], 201);
}

}
