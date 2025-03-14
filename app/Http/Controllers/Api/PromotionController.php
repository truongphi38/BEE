<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    // Lấy danh sách tất cả các promotions
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }

    // Lấy thông tin chi tiết của một promotion theo ID
    public function show($id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['message' => 'Promotion not found'], 404);
        }

        return response()->json($promotion);
    }
}
