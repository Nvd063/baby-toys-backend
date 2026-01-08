<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Public: Filtered active products).
     */
    public function index(Request $request)
    {
        $query = Product::with(['subCategory.category'])->where('is_active', true);
        if ($request->category_id) {
            $query->whereHas('subCategory.category', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }
        if ($request->sub_category_id) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $products = $query->paginate($request->get('per_page', 12));
        return ProductResource::collection($products);  // Updated: Use Resource collection
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product = Product::create($data);
        return new ProductResource($product);  // Updated: Use Resource
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::with(['subCategory.category'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return new ProductResource($product);  // Updated: Use Resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
            'description' => 'string',
            'image' => 'nullable|image|max:2048',
            'sub_category_id' => 'exists:sub_categories,id',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($data);
        return new ProductResource($product);  // Updated: Use Resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted']);  // No Resource needed
    }
}