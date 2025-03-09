@extends('admin.layout2')

@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Trị<strong> Đơn Hàng</strong></h1>
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>DH{{ $order->id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        @if ($order->status_id == 1)
                            <span class="badge bg-warning">Chờ xác nhận</span>
                        @elseif ($order->status_id == 2)
                            <span class="badge bg-success">Đã giao hàng</span>
                        @elseif ($order->status_id == 3)
                            <span class="badge bg-danger">Đã hủy</span>
                        @endif
                    </td>
                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Chi tiết</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>


@endsection
