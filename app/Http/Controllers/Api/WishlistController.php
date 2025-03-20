<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
    ]);

    $sessionId = session()->getId();
    // $userId = auth()->id() ?? null; 
    $userId = $request->user_id ?? auth()->id(); // Ưu tiên user_id từ request nếu có
    $productId = $request->product_id;

    $wishlist = Wishlist::where('product_id', $productId)
    ->where(function ($query) use ($userId, $sessionId) {
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
    })->first();

if ($wishlist) {
    $wishlist->increment('favorite_count'); // Nếu đã có trong wishlist, tăng 1
} else {
    Wishlist::create([
        'user_id' => $userId,
        'session_id' => $userId ? null : $sessionId,
        'product_id' => $productId,
        'favorite_count' => 1 // Đảm bảo lần đầu tiên thêm vào sẽ có giá trị 1
    ]);
}


    return response()->json(['message' => 'Sản phẩm đã được thêm vào yêu thích'], 200);
}
    public function syncWishlist()
    {
        if (!auth()->check()) {
            return;
        }

        $sessionId = session()->getId();
        $userId = auth()->id();

        // Cập nhật user_id cho các wishlist của session hiện tại
        Wishlist::where('session_id', $sessionId)
            ->update(['user_id' => $userId, 'session_id' => null]);
    }
}
