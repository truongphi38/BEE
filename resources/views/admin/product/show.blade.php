@extends('admin.layout2')
@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Product Details</strong></h1>       
        <a href="{{ route('product.index') }}" class="btn btn-secondary mb-3">Back to List</a>
        
        <div class="card">
            <div class="card-header">
                <h4>{{ $product->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Category:</strong> {{ $product->category->name }}</p>
                <p><strong>Base Price:</strong> {{ number_format($product->price, 0, ',', '.') }} vnđ</p>
                <p><strong>Discount Price:</strong> {{ number_format($product->discount_price, 0, ',', '.') }} vnđ</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>Created At:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>

                <h5>Sizes & Stock</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->sizes as $size)
                        <tr>
                            <td>{{ $size->name }}</td>
                            <td>{{ $size->pivot->stock }}</td>
                            <td>
                                @if ($size->pivot->stock > 0)
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <img src="{{ asset($product->img) }}" width="200" alt="Product Image">
            </div>
        </div>
    </div>
</main>

@endsection
