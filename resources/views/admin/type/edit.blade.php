@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white" style="font-weight: bolder;">Chỉnh Sửa Loại</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.type.update', $type->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tên Loại</label>
                        <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="img" class="form-label">Hình Ảnh</label>
                        <input type="file" class="form-control" id="img" name="img">
                        @if($type->img)
                            <img src="{{ asset($type->img) }}" alt="Product Image" width="100">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control">{{ $type->description }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Xác Nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
