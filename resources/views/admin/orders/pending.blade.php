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
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>

                            <td>
                                @if ($order->status_id == 1)
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                @elseif ($order->status_id == 2)
                                    <span class="badge bg-primary">Chờ thanh toán</span>
                                @elseif ($order->status_id == 3)
                                    <span class="badge bg-danger">Đã hủy</span>
                                @elseif ($order->status_id == 4)
                                    <span class="badge bg-success">Đã xác nhận</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>

                            <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>

                            <td>
                                {{ $order->promotion ? $order->promotion->code : 'Không áp dụng' }}
                            </td>

                            <td>
                                {{ $order->promotion ? $order->promotion->description : '' }}
                            </td>

                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>

                            <td>{{ $order->payment_method }}</td>

                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm mb-1">Chi tiết</a>

                                @if ($order->status_id == 1 || $order->status_id == 2)
                                    {{-- Xác nhận đơn hàng --}}
                                    <form action="{{ route('orders.update', $order->id) }}" method="POST"
                                        style="display: inline-block; margin-bottom: 5px;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?')">
                                            Xác nhận
                                        </button>
                                    </form>

                                    {{-- Hủy đơn hàng --}}
                                    <form id="cancelForm-{{ $order->id }}"
                                        action="{{ route('orders.delete', $order->id) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="showCancelReason({{ $order->id }})">
                                            Hủy đơn
                                        </button>

                                        <div id="reasonBox-{{ $order->id }}" style="display: none; margin-top: 10px;">
                                            <textarea name="cancel_reason" rows="2" class="form-control mb-1"
                                                placeholder="Nhập lý do hủy đơn..." required></textarea>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Xác nhận hủy</button>
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
            const box = document.getElementById('reasonBox-' + orderId);
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
        }
    </script>
@endsection
