<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    // Lấy tất cả các thanh toán
    public function index()
    {
        return response()->json(Payment::all(), 200);
    }

    // Lấy thông tin thanh toán theo ID
    public function show($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thanh toán'], 404);
        }
        return response()->json($payment, 200);
    }

    // Thêm mới thanh toán
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Lưu ý: khóa chính bảng orders là 'id'
            'method' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'payment_date' => 'nullable|date',
            'status_id' => 'required|integer',
        ]);

        // Kiểm tra đơn hàng tồn tại
        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    // Cập nhật thanh toán
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thanh toán'], 404);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'payment_date' => 'nullable|date',
            'status_id' => 'required|integer',
        ]);

        $payment->update($request->all());
        return response()->json($payment, 200);
    }

    // Xóa thanh toán
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Không tìm thấy thanh toán'], 404);
        }

        $payment->delete();
        return response()->json(['message' => 'Xóa thành công'], 200);
    }
}
