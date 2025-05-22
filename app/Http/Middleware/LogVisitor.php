<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogVisitor {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $today = now()->startOfDay();

        if ($request->is('admin/*')) {
            return $next($request);
        }

        $existing = DB::table('visitors')
                    ->where('ip_address', $ip)
                    ->where('visited_at', $today)
                    ->first();

        if ($existing) {
            DB::table('visitors')
                ->where('id', $existing->id)
                ->update([
                    'updated_at' => now(),
                    'visit_count' => $existing->visit_count + 1,
                ]);
        } else {
            DB::table('visitors')->insert([
                'ip_address' => $ip,
                'visited_at' => $today,
                'visit_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
