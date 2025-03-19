<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use App\Models\Promotion;

class OrderController extends Controller
{

    public function index()
    {
        $promotion = Promotion::get();
        $orders = Order::orderBy('id', 'desc')->get();
        return view('admin.orders.index', compact('orders','promotion'));
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
    ]);

    DB::beginTransaction();
    try {
        // 1. Tạo đơn hàng ban đầu với subtotal = 0
        $order = Order::create([
            'user_id' => $request->user_id,
            'subtotal' => 0, // Tạm thời 0, lát cập nhật lại
            'total_amount' => 0, // Tạm thời 0
            'status_id' => 1, // Chờ xác nhận
            'promotion_id' => $request->promotion_id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subtotal = 0; // Biến để tính tổng tiền hàng
        $orderDetails = [];

        // 2. Lưu chi tiết đơn hàng và kiểm tra kho hàng
        foreach ($request->products as $product) {
            $variant = ProductVariant::find($product['productvariant_id']);

            if (!$variant || $variant->stock_quantity < $product['quantity']) {
                DB::rollBack(); // Hủy giao dịch nếu có sản phẩm không đủ hàng
                return response()->json(['message' => 'Sản phẩm không đủ hàng'], 400);
            }

            $total_price = $variant->price * $product['quantity'];
            $subtotal += $total_price;

            $orderDetails[] = [
                'order_id' => $order->id,
                'productvariant_id' => $product['productvariant_id'],
                'quantity' => $product['quantity'],
                'total_price' => $total_price,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 3. Trừ kho hàng
            $variant->decrement('stock_quantity', $product['quantity']);
        }

        // 4. Chèn tất cả OrderDetail một lần (tối ưu hiệu suất)
        OrderDetail::insert($orderDetails);

        // 5. Tính lại khuyến mãi dựa trên tổng tiền hàng
        $discount = 0;
        if (!empty($request->promotion_id)) {
            $promotion = Promotion::find($request->promotion_id);
            if ($promotion) {
                $discount = ($subtotal * $promotion->discount_percent) / 100;
            }
        }

        // 6. Cập nhật lại subtotal và total_amount trong đơn hàng
        $order->update([
            'subtotal' => $subtotal,
            'total_amount' => max(0, $subtotal - $discount)
        ]);

        DB::commit();
        return response()->json(['message' => 'Đơn hàng đã được tạo thành công!', 'order' => $order], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Đã xảy ra lỗi khi tạo đơn hàng!', 'error' => $e->getMessage()], 500);
    }
}

}
