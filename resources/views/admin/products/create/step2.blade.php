@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Step 2: Select Subcategory</h2>
    <form action="{{ route('admin.products.create.post2') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Select Existing Subcategory:</label>
            <select name="sub_category_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($subCategories as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Or Custom Subcategory:</label>
            <input type="text" name="custom_sub_category" class="form-control" placeholder="Enter new subcategory name">
        </div>
        <a href="{{ route('admin.products.create.step1') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Next: Product Details</button>
    </form>
</div>
@endsection