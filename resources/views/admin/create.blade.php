@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Thêm Sản Phẩm Mới</strong></h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Quay lại</a>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Thông tin cơ bản --}}
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">Chọn danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="type_id" class="form-label">Loại</label>
                <select name="type_id" id="type_id" class="form-select" required>
                    <option value="">Chọn loại</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="img" class="form-label">Ảnh sản phẩm</label>
                <input type="file" name="img" id="img" class="form-control" accept="image/*" required>
            </div>

            <hr>

            {{-- Phần biến thể (variant) --}}
            <h4>Biến thể sản phẩm (Màu, Size, Giá, Tồn kho, Giá khuyến mãi)</h4>

            <table class="table" id="variants-table">
                <thead>
                    <tr>
                        <th>Màu</th>
                        <th>Size</th>
                        <th>Giá (VNĐ)</th>
                        <th>Giá khuyến mãi (VNĐ)</th>
                        <th>Tồn kho</th>
                        <th><button type="button" id="add-variant" class="btn btn-success btn-sm">Thêm biến thể</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="variants[0][color_id]" class="form-select" required>
                                <option value="">Chọn màu</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="variants[0][size]" class="form-control" placeholder="VD: S, M, L" required>
                        </td>
                        <td>
                            <input type="number" name="variants[0][price]" class="form-control" min="0" required>
                        </td>
                        <td>
                            <input type="number" name="variants[0][discount_price]" class="form-control" min="0" value="0">
                        </td>
                        <td>
                            <input type="number" name="variants[0][stock_quantity]" class="form-control" min="0" value="0" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        let variantIndex = 1;
        $('#add-variant').click(function(){
            let newRow = `
            <tr>
                <td>
                    <select name="variants[${variantIndex}][color_id]" class="form-select" required>
                        <option value="">Chọn màu</option>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="VD: S, M, L" required>
                </td>
                <td>
                    <input type="number" name="variants[${variantIndex}][price]" class="form-control" min="0" required>
                </td>
                <td>
                    <input type="number" name="variants[${variantIndex}][discount_price]" class="form-control" min="0" value="0">
                </td>
                <td>
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" class="form-control" min="0" value="0" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa</button>
                </td>
            </tr>
            `;
            $('#variants-table tbody').append(newRow);
            variantIndex++;
        });

        // Xóa dòng biến thể
        $(document).on('click', '.remove-variant', function(){
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection
