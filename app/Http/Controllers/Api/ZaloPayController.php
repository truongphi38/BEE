<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ZaloPayController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('zalopay');
    }

    /**
     * API tạo đơn hàng thanh toán ZaloPay
     */
    public function createPayment(Request $request)
    {
        $amount = $request->input('amount', 10000); // Số tiền
        $transID = rand(0, 1000000);
        $embed_data = json_encode(["merchantinfo" => "embeddata123"]);
        $items = json_encode([["itemid" => "knb", "itemname" => "kim nguyen bao", "itemprice" => $amount, "itemquantity" => 1]]);

        $order = [
            "app_id" => $this->config["app_id"],
            "app_time" => round(microtime(true) * 1000),
            "app_trans_id" => date("ymd") . "_" . $transID,
            "app_user" => "user123",
            "item" => $items,
            "embed_data" => $embed_data,
            "amount" => $amount,
            "description" => "Thanh toán đơn hàng #$transID",
            "bank_code" => ""
        ];

        // Tạo chữ ký (MAC)
        $data = implode("|", [
            $order["app_id"], $order["app_trans_id"], $order["app_user"],
            $order["amount"], $order["app_time"], $order["embed_data"], $order["item"]
        ]);
        $order["mac"] = hash_hmac("sha256", $data, $this->config["key1"]);

        // Gửi request tới ZaloPay
        $response = Http::asForm()->post($this->config["endpoint"] . "/create", $order);
        $result = $response->json();

        if (isset($result['order_url'])) {
            return response()->json([
                "success" => true,
                "payment_url" => $result['order_url'],
                "qr_code" => $result['qr_code'],
                "zp_trans_token" => $result['zp_trans_token']
            ]);
        }

        return response()->json(["error" => "Không thể tạo thanh toán!", "details" => $result], 400);
    }

    /**
     * API kiểm tra trạng thái giao dịch
     */
    public function checkPaymentStatus(Request $request)
    {
        $appTransId = $request->input('app_trans_id');

        if (!$appTransId) {
            return response()->json(["error" => "Thiếu app_trans_id"], 400);
        }

        $mac = hash_hmac("sha256", $appTransId . "|" . $this->config["key1"], $this->config["key1"]);

        $response = Http::asForm()->post($this->config["endpoint"] . "/query", [
            "app_id" => $this->config["app_id"],
            "app_trans_id" => $appTransId,
            "mac" => $mac
        ]);

        return response()->json($response->json());
    }
}
