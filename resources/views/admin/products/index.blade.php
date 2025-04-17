@extends('admin.layout')

@section('title')
    Admin - Products
@endsection

@section('body')
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin - Products</h1>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
                <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $key => $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->image)
                            <img src='{{ asset("$product->image") }}' width="50" height="50" alt="{{ $product->name }}">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('admin.products.destroy', $product->id) }}" class="btn btn-secondary" 
                            onclick='deleteOperation(event, "product-delete-{{ $key }}")'
                        >
                            Delete
                        </a>
                        <form id="product-delete-{{ $key }}" action="{{ route('admin.products.destroy', $product->id) }}"
                            method="POST" style="display: none;">
                           @csrf
                           @method('DELETE')
                       </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
