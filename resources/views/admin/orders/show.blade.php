@extends('admin.layout2')

@section('content')
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

    <h4>Thông tin khách hàng</h4>
    <p><strong>Tên:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>

    <h4>Sản phẩm trong đơn hàng</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->productVariant->product->name }} - {{ $detail->productVariant->size }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->productVariant->price, 0, ',', '.') }} đ</td>
                    <td>{{ number_format($detail->total_price, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Tổng đơn hàng:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ</p>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
