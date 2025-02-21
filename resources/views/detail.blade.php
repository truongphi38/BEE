@extends('layout')
@section('titlepage','Website bán hàng online LaraSu24')
@section('title','Welcome')

@section('content')

<div class="container_cart">
        <div class="product-detail">
            <div class="product-image">
                <img src="img/hinh1.webp" alt="Product 1">
            </div>
            <div class="product-info">
                <h2>Tên Sản Phẩm</h2>
                <p>Mô tả chi tiết về sản phẩm. Sản phẩm này có những đặc điểm nổi bật và phù hợp cho...</p>
                <p>Giá: $99.99</p>
                <button class="order-button">Đặt Hàng</button>
            </div>
        </div>

        <div class="related-products">
            <h3>Sản Phẩm Liên Quan</h3>
            <div class="product">
                <div class="product-image">
                    <img src="img/hinh2.webp" alt="Product 2">
                </div>
                <div class="product-info">
                    <h4>Tên Sản Phẩm 2</h4>
                    <p>Giá: $79.99</p>
                </div>
            </div>
            <div class="product">
                <div class="product-image">
                    <img src="img/hinh3.webp" alt="Product 3">
                </div>
                <div class="product-info">
                    <h4>Tên Sản Phẩm 3</h4>
                    <p>Giá: $89.99</p>
                </div>
            </div>
            
            
        </div>
    </div>

@endsection