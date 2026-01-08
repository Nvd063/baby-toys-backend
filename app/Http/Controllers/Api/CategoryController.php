<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource (Public: Active categories with nested subs/products).
     */
    public function index(Request $request)
    {
        $query = Category::where('is_active', true);
        if ($request->with_products) {
            $query->with(['subCategories' => function ($q) {
                $q->where('is_active', true)->with(['products' => function ($p) {
                    $p->where('is_active', true);
                }]);
            }]);
        } else {
            $query->with(['subCategories' => function ($q) {
                $q->where('is_active', true);
            }]);
        }
        $categories = $query->paginate($request->get('per_page', 10));
        return CategoryResource::collection($categories);  // Updated: Use Resource collection
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'is_active' => 'boolean',
        ]);
        $category = Category::create($data);
        return new CategoryResource($category);  // Updated: Use Resource
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['subCategories' => function ($q) {
            $q->where('is_active', true)->with(['products' => function ($p) {
                $p->where('is_active', true);
            }]);
        }]);
        return new CategoryResource($category);  // Updated: Use Resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'string|max:255|unique:categories,name,' . $category->id,
            'is_active' => 'boolean',
        ]);
        $category->update($data);
        return new CategoryResource($category);  // Updated: Use Resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted']);  // No Resource needed for simple message
    }
}