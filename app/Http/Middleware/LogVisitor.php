<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class LogVisitor {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip(); // Lấy IP người dùng
        $today = Carbon::today()->toDateString(); // Lấy ngày hiện tại

        // Kiểm tra xem IP này đã được ghi nhận trong hôm nay chưa
        if (!Visitor::where('ip_address', $ip)->where('visited_at', $today)->exists()) {
            Visitor::create([
                'ip_address' => $ip,
                'visited_at' => $today
            ]);
        }

        return $next($request);
    }
}
