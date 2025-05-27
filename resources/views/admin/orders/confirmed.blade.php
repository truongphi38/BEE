@extends('admin.layout2')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">Quản Trị<strong> Đơn Hàng</strong></h1>
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ngày cập nhật</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền sản phẩm</th>
                        <th>Voucher</th>
                        <th>Mô tả</th>
                        <th>Tổng tiền</th>
                        <th>Hình thức thanh toán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>DH{{ $order->id }}</td>
                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-info">{{ $order->status->name }}</span></td>
                            <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                            <td>{{ optional($order->promotion)->code ?? 'Không áp dụng' }}</td>
                            <td>{{ optional($order->promotion)->description ?? '' }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Chi tiết</a>
                                @if (in_array($order->status_id, [3, 4]))
                                    <!-- Chỉ hiển thị khi đơn hàng có trạng thái 3 hoặc 4 -->
                                    <form action="{{ route('orders.update', $order->id) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?')">
                                            Tiến Hành Giao Hàng
                                        </button>
                                    </form>
                                    <form id="cancelForm-{{ $order->id }}" action="{{ route('orders.delete', $order->id) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="showCancelReason({{ $order->id }})">
                                            Hủy đơn hàng
                                        </button>
                                        <div id="reasonBox-{{ $order->id }}" style="display: none; margin-top: 10px;">
                                            <textarea name="cancel_reason" id="cancel_reason-{{ $order->id }}" rows="3"
                                                placeholder="Nhập lý do hủy đơn..." required></textarea>
                                            <br>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Xác nhận hủy
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function showCancelReason(orderId) {
            document.getElementById('reasonBox-' + orderId).style.display = 'block';
        }
    </script>
@endsection
