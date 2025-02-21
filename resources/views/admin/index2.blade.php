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
                        <th>Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Base Price</th>
                        <th class="text-danger bold">Discount Price</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name}}</td>
                        <td><img src="{{ asset('img/' . $item->img) }}" width="80" alt=""></td>
                        <td>{{ number_format($item->price,0,',','.')  }} vnđ</td>
                        <td class="text-danger" >{{ number_format($item->discount_price,0,',','.')  }} vnđ</td>
                        <td>{{ $item->description}}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="action-icons">
                            <a  href="{{ route('admin.product.edit', $item->id) }}">Edit</a> 
                            
                            <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;">Delete</button>
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
