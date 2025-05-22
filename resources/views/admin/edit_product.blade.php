@extends('layouts.admin')

@section('content')
<h2>Sửa sản phẩm</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name">Tên sản phẩm</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="category_id">Danh mục</label>
        <select name="category_id" id="category_id" class="form-control" required>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="type_id">Loại sản phẩm</label>
        <select name="type_id" id="type_id" class="form-control" required>
            @foreach ($types as $type)
            <option value="{{ $type->id }}" @selected(old('type_id', $product->type_id) == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="description">Mô tả</label>
        <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Ảnh hiện tại</label><br>
        @if($product->img)
            <img src="{{ asset($product->img) }}" alt="Ảnh sản phẩm" width="150">
        @else
            Chưa có ảnh
        @endif
    </div>

    <div class="mb-3">
        <label for="img">Thay ảnh mới</label>
        <input type="file" name="img" id="img" class="form-control" accept="image/*">
    </div>

    <hr>
    <h4>Biến thể màu</h4>

    <div id="variantContainer">
        @foreach ($product->productVariants as $variant)
        <div class="variantRow d-flex gap-3 align-items-center mb-2">
            <select name="color_id[]" class="form-select" required>
                <option value="">Chọn màu</option>
                @foreach ($colors as $color)
                <option value="{{ $color->id }}" @selected($color->id == $variant->color_id)>{{ $color->name }}</option>
                @endforeach
            </select>

            <input type="number" name="variant_price[]" class="form-control" placeholder="Giá" min="1" value="{{ $variant->price }}" required>

            <input type="number" name="variant_discount_price[]" class="form-control" placeholder="Giá giảm (nếu có)" min="0" value="{{ $variant->discount_price }}">

            <input type="number" name="stock_quantity[]" class="form-control" placeholder="Tồn kho" min="0" value="{{ $variant->stock_quantity }}" required>

            <button type="button" class="btn btn-danger btnRemoveVariant">Xóa</button>
        </div>
        @endforeach
    </div>

    <button type="button" id="btnAddVariant" class="btn btn-primary mb-3">Thêm biến thể màu</button>

    <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
</form>

<script>
    document.getElementById('btnAddVariant').addEventListener('click', function() {
        const container = document.getElementById('variantContainer');
        // clone mẫu đầu tiên hoặc tạo mới
        const newRow = container.children[0].cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelector('select').value = '';
        container.appendChild(newRow);
    });

    document.getElementById('variantContainer').addEventListener('click', function(e) {
        if (e.target.classList.contains('btnRemoveVariant')) {
            const rows = document.querySelectorAll('.variantRow');
            if (rows.length > 1) {
                e.target.closest('.variantRow').remove();
            } else {
                alert('Phải có ít nhất một biến thể.');
            }
        }
    });
</script>
@endsection
