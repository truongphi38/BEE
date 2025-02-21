<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('home');
    }
    public function productHome()
    {
        $newestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        $bestSellingProducts = Product::orderBy('sales_count', 'desc')->take(6)->get();
        $mostViewedProducts = Product::orderBy('views_count', 'desc')->take(6)->get();

        return view('product_home', compact('newestProducts', 'bestSellingProducts', 'mostViewedProducts'));
    }
    public function khuyenmai(){
        $newProducts=Product::newProducts(6)->get();
        $bestsellerProducts=Product::bestsellerProducts(6)->get();
        $instockProducts=Product::instockProducts(3)->get();
        return view('khuyenmai', compact('newProducts', 'bestsellerProducts', 'instockProducts'));
    }
    public function muahe2024(){
        $newProducts=Product::newProducts(6)->get();
        $bestsellerProducts=Product::bestsellerProducts(6)->get();
        $instockProducts=Product::instockProducts(3)->get();
        return view('muahe2024', compact('newProducts', 'bestsellerProducts', 'instockProducts'));
    }
}
