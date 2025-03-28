<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        // Tổng lượt truy cập
        $totalVisitors = Visitor::count();
        // Lượt truy cập hôm nay
        $todayVisitors = Visitor::where('visited_at', Carbon::today())->count();
        // Lấy số lượng user đăng ký trong tuần này
        $currentWeekUsers = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    
        // Lấy số lượng user đăng ký trong tuần trước
        $lastWeekUsers = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
    
        // Tránh chia cho 0 để không bị lỗi
        if ($lastWeekUsers == 0) {
            $growthPercentage = $currentWeekUsers > 0 ? 100 : 0;
        } else {
            $growthPercentage = (($currentWeekUsers - $lastWeekUsers) / $lastWeekUsers) * 100;
        }
    
        return view('admin.index', compact('totalUsers', 'growthPercentage','totalVisitors','todayVisitors'));
    }
    
    

}
