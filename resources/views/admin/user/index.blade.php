@extends('admin.layout2')
@section('content')


<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>User</strong> Management</h1>
        
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Create At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->roles->name }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="action-icons">
                            <a href="">Edit</a>
                            -
                            <a href="">Delete</a>
                            {{-- <form action="{{ route('admin2.products.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE') --}}
                                {{-- <a href="{{ route('products.edit', $item->id) }}">Edit</a> -
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;">Delete</button> --}}
                            {{-- </form> --}}
                            
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
    
            
        

    </div>
</main>

@endsection
