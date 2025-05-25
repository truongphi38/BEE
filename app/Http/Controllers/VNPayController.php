<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        // Lấy user_id từ user đang đăng nhập
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Bạn phải đăng nhập mới được tạo đơn'], 401);
        }

        // Tạo đơn hàng với trạng thái chờ thanh toán
        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $request->amount,
            'status_id' => 1, // trạng thái 'pending'
            'subtotal' => $request->amount,
        ]);

        // Các biến môi trường VNPay
        $vnp_TmnCode = env('VNPAY_TMNCODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_Returnurl = env('VNPAY_RETURN_URL');

        $vnp_TxnRef = $order->id;
        $vnp_Amount = $request->amount * 100; // nhân 100 vì VNPay quy đổi
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $vnp_TxnRef;
        $vnp_OrderType = 'other';
        $vnp_Locale = 'vn';
        $vnp_BankCode = $request->input('bank_code', '');
        $vnp_IpAddr = $request->ip();
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $hashData = '';
        $query = '';
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $hashData = rtrim($hashData, '&');
        $query = rtrim($query, '&');
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_Url = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return response()->json([
            'code' => '00',
            'message' => 'Tạo liên kết thanh toán thành công',
            'payment_url' => $vnp_Url,
            'order_id' => $vnp_TxnRef
        ]);
    }
    public function vnpayReturn(Request $request)
{
    $responseCode = $request->get('vnp_ResponseCode');

    if ($responseCode == '00') {
        return response()->json(['status' => 'success', 'message' => 'Thanh toán thành công']);
    } else {
        return response()->json(['status' => 'fail', 'message' => 'Thanh toán thất bại']);
    }
}

}
