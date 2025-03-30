<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZaloPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $config = config('zalopay');

        $transID = rand(0, 1000000);
        $callbackUrl = "http://localhost:4200/trangchu"; // URL cần redirect sau khi thanh toán

        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000),
            "app_trans_id" => date("ymd") . "_" . $transID,
            "app_user" => "user123",
            "item" => '[]',
            "embed_data" => json_encode(["redirecturl" => $callbackUrl]), // Thêm URL chuyển hướng
            "amount" => $request->amount,
            "description" => "Thanh toán đơn hàng #$transID",
            "bank_code" => ""
        ];

        // Tạo chữ ký bảo mật (MAC)
        $data = implode("|", [
            $order["app_id"],
            $order["app_trans_id"],
            $order["app_user"],
            $order["amount"],
            $order["app_time"],
            $order["embed_data"],
            $order["item"]
        ]);
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        // Gửi yêu cầu đến API ZaloPay
        $response = Http::asForm()->post($config["endpoint"], $order);
        $result = $response->json();

        if (isset($result['order_url'])) {
            return response()->json([
                'status' => 'success',
                'payment_url' => $result['order_url']
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $result['return_message'] ?? 'Lỗi không xác định'
            ], 400);
        }
    }


}
