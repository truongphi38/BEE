@extends('admin.layout2')
@section('content')


<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Product</strong> Management</h1>       
        <a href="{{ route('product.create') }}" class="btn btn-primary">Add Product</a>
            <table  id="myTable" class="table table-striped" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Danh Mục</th>
                        <th>Loại</th>
                        <th>Hình Ảnh</th>
                        <th>Giá</th>
                        <th class="text-danger bold">Giá Giảm</th>
                        <th>Mô Tả</th>
                        <th>Ngày Tạo</th>
                        <th>Ngày Cập Nhật</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name}}</td>
                        <td>{{ $item->type->name}}</td>
                        <td><img src="{{ asset($item->img) }}" width="80" alt="">
                        </td>
                        
                        <td>{{ number_format($item->price,0,',','.')  }} VNĐ</td>
                        <td class="text-danger" >{{ number_format($item->discount_price,0,',','.')  }} VNĐ</td>  
                        <td>{{ $item->description}}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="action-icons">
                            <a class="text-success" href="{{ route('admin.product.show', $item->id) }}">Chi tiết</a>
                            <a  href="{{ route('admin.product.edit', $item->id) }}"><span class="text-black">| </span>Chỉnh Sửa</a> 
                            
                            <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;"><span class="text-black">|</span> Xoá</button>
                            </form>
                        </td>
                        
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-center mt-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div> --}}
            
    </div>
</main>

@endsection
