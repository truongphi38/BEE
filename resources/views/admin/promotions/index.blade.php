@extends('admin.layout2')
@section('content')


<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Lý <strong>Mã Khuyến Mãi</strong></h1>
        <a href="{{ route('promotion.create')}}" class="btn btn-primary">Thêm Mã Khuyến Mãi</a>

        
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã</th>
                        <th>Phần Trăm Giảm</th>
                        <th>Mô Tả</th>
                        <th>Ngày Bắt Đầu</th>
                        <th>Ngày Kết Thúc</th>
                        <th>Ngày Tạo</th>
                        <th>Ngày Cập Nhật</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ number_format($item->discount_percent,0) }}%</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at) }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->updated_at) }}</td>
                        <td class="action-icons">
                            <a href="{{ route('promotion.edit',$item->id)}}" >Chỉnh Sửa</a>                             
                            <form action="{{ route('promotion.delete',$item->id)}}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;"> <span class="text-black">|</span> Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
    
            
        

    </div>
</main>

@endsection
