@extends('admin.layout2')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh Sửa Sản Phẩm</h2>
    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>
            <div class="col-md-6">
                <label for="discount_price" class="form-label">Giá Khuyến Mãi</label>
                <input type="number" class="form-control" id="discount_price" name="discount_price" value="{{ $product->discount_price }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh Mục</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Loại</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="img" name="img">
            @if($product->img)
                <img src="{{ asset($product->img) }}" alt="Product Image" width="100">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
