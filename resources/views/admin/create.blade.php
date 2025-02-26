@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 style="font-weight: bolder" class="mb-0 text-white">New Product</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-primary">{{ session('success') }}</div>
                @endif
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label  class="form-label fw-bolder">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                    </div>

                    <div id="variant-container">
                        <div class="variant-group">
                            <label class="form-label fw-bolder">Size</label>
                            <select name="size[]" class="form-select" required>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <label class="form-label fw-bolder">Stock</label>
                            <input type="number" name="stock[]" class="form-control" min="1" required>
                            <button type="button" class="btn btn-danger mt-2 remove-variant">Cancel</button>
                        </div>
                    </div>
                    <button type="button" id="add-variant" class="btn btn-secondary mt-2">Add Size</button>
                    

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bolder">Price</label>
                            <input type="number" name="price" class="form-control" placeholder="Nhập giá sản phẩm" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bolder">Discount Price</label>
                            <input type="number" name="discount_price" class="form-control" placeholder="Nhập giá giảm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bolder">Description</label>
                        <textarea name="description" class="form-control" placeholder="Nhập mô tả sản phẩm"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bolder">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Choose Category</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bolder">Image</label>
                        <input type="file" name="img" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">ADD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    document.getElementById('add-variant').addEventListener('click', function () {
    let variantContainer = document.getElementById('variant-container');
    let newVariant = document.createElement('div');
    newVariant.classList.add('variant-group');
    newVariant.innerHTML = `
        <label class="form-label">Size</label>
        <select name="size[]" class="form-select" required>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
        </select>
        <label class="form-label">Stock</label>
        <input type="number" name="stock[]" class="form-control" min="1" required>
        <button type="button" class="btn btn-danger remove-variant">Cancel</button>
    `;
    variantContainer.appendChild(newVariant);
});

// Xóa biến thể
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.parentElement.remove();
    }
});

</script>
@endsection
