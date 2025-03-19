@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white" style="font-weight: bolder;">Thêm Danh Mục</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-primary">{{ session('success') }}</div>
                @endif

                
                <form action="{{ route('admin.category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập tên" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" placeholder="Nhập mô tả"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
