@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Chi Tiết Sản Phẩm</strong></h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Quay lại</a>

        <button id="btn-show-reviews" class="btn btn-primary mb-3">Đánh giá</button>

        <div class="card">
            <div class="card-header">
                @if ($product)
                    <img src="{{ asset($product->img) }}" width="200" alt="Product Image">
                    <h4 class="mt-2">Sản Phẩm : {{ $product->name }}</h4>
                @else
                    <p>Product not found.</p>
                @endif
            </div>

            <div class="card-body">
                @if ($product)
                    <p><strong>Danh mục:</strong> {{ optional($product->category)->name ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Loại:</strong> {{ optional($product->type)->name ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Mô tả:</strong> {{ $product->description }}</p>
                    <p><strong>Giá gốc (từ màu đầu tiên):</strong> {{ number_format($basePrice, 0, ',', '.') }} VNĐ</p>
                    <p><strong>Ngày tạo:</strong> {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '' }}</p>

                    <h5><strong>Sizes & Tồn kho</strong></h5>
                    <table id="product-details" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Màu</th>
                                <th>Size</th>
                                <th>Tồn kho</th>
                                <th>Giá</th>
                                <th>Giá khuyến mãi</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->productVariants as $variant)
                                <tr>
                                    <td>{{ optional($variant->color)->name ?? '-' }}</td>
                                    <td>{{ $variant->size }}</td>
                                    <td>{{ $variant->stock_quantity }}</td>
                                    <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        @if($variant->discount_price && $variant->discount_price > 0)
                                            {{ number_format($variant->discount_price, 0, ',', '.') }} VNĐ
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($variant->stock_quantity > 0)
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Khu vực hiển thị đánh giá -->
    <div id="reviews-section" class="d-none container-fluid p-0 mt-4">
        <h4>Đánh giá sản phẩm</h4>
        <div id="reviews-content"></div>
    </div>
</main>

{{-- JQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $("#btn-show-reviews").click(function () {
            let productId = {{ $product->id }};
            let reviewSection = $("#reviews-section");
            let productDetails = $("#product-details");

            if (reviewSection.hasClass("d-none")) {
                $.ajax({
                    url: "/products/" + productId + "/reviews",
                    type: "GET",
                    success: function (data) {
                        productDetails.hide();
                        reviewSection.removeClass("d-none");
                        $("#reviews-content").html(data);
                        $("#btn-show-reviews").text("Thông tin");
                    },
                    error: function () {
                        alert("Không thể tải đánh giá, vui lòng thử lại!");
                    }
                });
            } else {
                reviewSection.addClass("d-none");
                productDetails.show();
                $("#btn-show-reviews").text("Đánh giá");
            }
        });
    });
</script>
@endsection
