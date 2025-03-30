@extends('admin.layout2')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <h1>Chi tiết đơn hàng : <span
                    style="color: black;font-size: larger;text-transform: uppercase">DH{{ $order->id }}</span></h1>

            <h4>Thông tin khách hàng :</h4>
            <p><strong>- Họ và tên:</strong><span style="color:navy ;font-size: larger;text-transform: uppercase">
                    {{ $order->user->name }}</span></p>
            <p><strong>- Email:</strong> <span style="color:navy;font-size: larger">{{ $order->user->email }}</span></p>
            <p><strong>- Số điện thoại:</strong> <span style="color:navy;font-size: larger">{{ $order->user_phone }}</span>
            </p>
            <p><strong>- Địa chỉ giao:</strong> <span
                    style="color:navy ;font-size: larger;text-transform: uppercase">{{ $order->user_address }}</span></p>

            <h4>Sản phẩm trong đơn hàng :</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên sản phẩm - Size</th>
                        <th>Hình Ảnh</th>
                        <th>Số lượng</th>
                        <th>Đơn Giá</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $detail)
                        <tr>
                            <td>{{ $detail->productVariant->product->name }} - {{ $detail->productVariant->size }}</td>

                            <td><img src="{{ asset($detail->productVariant->product->img) }}" width="80" alt="">
                            </td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->productVariant->price, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($detail->total_price, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p><strong style="color: red;text-transform: uppercase">Tổng đơn hàng:</strong>
                <span style="color: red;text-transform: uppercase;font-size: larger">
                    {{ number_format($order->orderDetails->sum('total_price'), 0, ',', '.') }} vnđ</span>
            </p>


            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </main>
@endsection
