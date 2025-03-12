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

                
                <form action="{{ route('type.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên Loại</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter type name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" placeholder="Enter category description"></textarea>
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
