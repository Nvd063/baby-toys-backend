@extends('layouts.admin') {{-- Assume admin layout hai --}}

@section('content')
<div class="container">
    <h2>Step 1: Select Category</h2>
    <form action="{{ route('admin.products.create.post1') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Select Existing Category:</label>
            <select name="category_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Or Custom Category:</label>
            <input type="text" name="custom_category" class="form-control" placeholder="Enter new category name">
        </div>
        <button type="submit" class="btn btn-primary">Next: Subcategory</button>
    </form>
</div>
@endsection