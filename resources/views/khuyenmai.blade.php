@extends('layout')
@section('titlepage','Website bán hàng online LaraSu24')
@section('title','Welcome')

@section('content')
<div class="container">
    <h2>Sản Phẩm Mới</h2>
    <div class="product-box">
        @foreach ($newProducts as $item)
            <div class="product">
                <img src="{{$item->img}}" alt="" />
                <h3>{{$item->name}}</h3>
                <p>{{ number_format($item->price,0,',','.') }} VNĐ</p>
            </div>
        @endforeach
    </div>

    <h2>Bán chạy</h2>
    <div class="product-box">
        @foreach ($bestsellerProducts as $item)
            <div class="product">
                <img src="{{$item->img}}" alt="" />
                <h3>{{$item->name}}</h3>
                <p>{{ number_format($item->price,0,',','.') }} VNĐ</p>
                @if ($item->category)
                    ({{ $item->category->name }})
                @endif
            </div>
        @endforeach
    </div>

    <h2>Tồn kho</h2>
    <div class="product-box">
        @foreach ($instockProducts as $item)
            <div class="product">
                <img src="{{$item->img}}" alt="" />
                <h3>{{$item->name}}</h3>
                <p>{{ number_format($item->price,0,',','.') }} VNĐ</p>
                @if ($item->category)
                    ({{ $item->category->name }})
                @endif
            </div>
        @endforeach
    </div>
    
</div>

@endsection