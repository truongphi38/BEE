@extends('admin.layout2')
@section('content')


<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Lý <strong>Loại</strong></h1>
        <a href="{{route('type.create')}}" class="btn btn-primary">Thêm Loại</a>

        
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Hình Ảnh</th>
                        <th>Mô tả</th>
                        <th>Ngày Tạo</th>
                        <th>Ngày Cập Nhật</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($types as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td><img src="{{ asset($item->img) }}" width="80" alt="">
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td class="action-icons">
                            <a href="{{ route('admin.type.edit', $item->id) }}" >Chỉnh Sửa</a>                             
                            <form action="{{ route('admin.type.destroy', $item->id) }}" method="POST" 
                                style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xoá?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;"> 
                                    <span class="text-black">|</span> Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
    
            
        

    </div>
</main>

@endsection
