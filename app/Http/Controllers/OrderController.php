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
}
