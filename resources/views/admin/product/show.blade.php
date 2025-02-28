@extends('admin.layout2')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3"><strong>Product Details</strong></h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back to List</a>

            <div class="card">
                <div class="card-header">
                    <img src="{{ asset($product->img) }}" width="200" alt="Product Image">
                    @if ($product)
                        <h4 class="mt-2">Product : {{ $product->name }}</h4>
                        {{-- <p><strong>Category:</strong> {{ optional($product->category)->name }}</p> --}}
                    @else
                        <p>Product not found.</p>
                    @endif


                </div>
                <div class="card-body">
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Base Price:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    <p><strong>Discount Price:</strong> {{ number_format($product->discount_price, 0, ',', '.') }} VNĐ</p>
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
                            @foreach ($product->product_variants as $variant)
                                <tr>
                                    <td>{{ $variant->size }}</td> 
                                    <td>{{ $variant->stock_quantity }}</td>
                                    <td>
                                        @if ($variant->stock_quantity > 0)
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </main>
@endsection
