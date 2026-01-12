@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Step 3: Product Details</h2>
    <p>Subcategory: {{ $subCategory->name }}</p>
    <form action="{{ route('admin.products.create.post3') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label>Image (optional):</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <a href="{{ route('admin.products.create.step2') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-success">Create Product</button>
    </form>
</div>
@endsection