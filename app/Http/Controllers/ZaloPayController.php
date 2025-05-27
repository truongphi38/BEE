<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZaloPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'user_id' => 'required|exists:users,id',
        ]);

        $config = config('zalopay');
        $transID = rand(100000, 999999);
        $appTransID = date("ymd") . "_" . $transID;

        // Lưu đơn hàng
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->amount,
            'status_id' => 1, // pending
            'payment_method' => 'zalopay',
            'promotion_id' => $request->promotion_id ?? null,
            'zalopay_app_trans_id' => $appTransID,
        ]);

        $callbackUrl = route('zalopay.callback');

        $orderData = [
            "app_id" => $config["app_id"],
            "app_trans_id" => $appTransID,
            "app_user" => (string) $request->user_id,
            "app_time" => round(microtime(true) * 1000),
            "item" => '[]',
            "embed_data" => json_encode(["order_id" => $order->id]),
            "amount" => $request->amount,
            "description" => "Thanh toán đơn hàng #{$order->id}",
            "bank_code" => ""
        ];

        $data = implode("|", [
            $orderData["app_id"],
            $orderData["app_trans_id"],
            $orderData["app_user"],
            $orderData["amount"],
            $orderData["app_time"],
            $orderData["embed_data"],
            $orderData["item"]
        ]);

        $orderData["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $response = Http::asForm()->post($config["endpoint"], $orderData);
        $result = $response->json();

        if (isset($result['order_url'])) {
            return response()->json([
                'status' => 'success',
                'payment_url' => $result['order_url'],
                'order_id' => $order->id
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $result['return_message'] ?? 'Lỗi không xác định'
            ], 400);
        }
    }

    public function callback(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['data']) || !isset($data['mac'])) {
            return response()->json(['code' => 1, 'message' => 'Dữ liệu không hợp lệ'], 400);
        }

        $config = config('zalopay');
        $mac = hash_hmac("sha256", $data["data"], $config["key2"]);

        if ($mac !== $data["mac"]) {
            return response()->json(['code' => 1, 'message' => 'MAC không hợp lệ'], 400);
        }

        $orderData = json_decode($data["data"], true);
        $orderId = $orderData["order_id"] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->status_id = 2; // success
                $order->save();
            }
        }

        return response()->json(['code' => 0, 'message' => 'Xác nhận thành công']);
    }
}
