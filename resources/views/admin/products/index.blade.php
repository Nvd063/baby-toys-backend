@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Products List</h2>
    <a href="{{ route('admin.products.create.step1') }}" class="btn btn-primary mb-3">Add New Product</a>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Subcategory</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->subCategory->name ?? 'N/A' }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" height="50" class="img-thumbnail">
                    @else
                        No Image
                    @endif
                </td>
                <td>
                    <a href="#" class="btn btn-sm btn-info">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No products found. Add one!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $products->links() }} 
</div>
@endsection