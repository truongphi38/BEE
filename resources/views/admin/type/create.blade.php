@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white" style="font-weight: bolder;">Loại Sản Phẩm</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-primary">{{ session('success') }}</div>
                @endif

                
                <form action="{{ route('type.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên Loại</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bolder">Hình Ảnh</label>
                        <input type="file" name="img" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" placeholder="Nhập mô tả"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm Loại</button>
                    </div>
                </form>
                

            </div>
        </div>
    </div>
</main>
@endsection
