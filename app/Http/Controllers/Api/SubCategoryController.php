<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource (Public: Active subs with products).
     */
    public function index(Request $request)
    {
        $query = SubCategory::where('is_active', true)->with(['category', 'products' => function ($q) {
            $q->where('is_active', true);
        }]);
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        $subCategories = $query->paginate($request->get('per_page', 10));
        return SubCategoryResource::collection($subCategories);  // Updated: Use Resource collection
    }

    /**
     * Store a newly created resource in storage (Admin only).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:sub_categories',
            'is_active' => 'boolean',
        ]);
        $subCategory = SubCategory::create($data);
        return new SubCategoryResource($subCategory);  // Updated: Use Resource
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        $subCategory->load(['category', 'products' => function ($q) {
            $q->where('is_active', true);
        }]);
        return new SubCategoryResource($subCategory);  // Updated: Use Resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $data = $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255|unique:sub_categories,name,' . $subCategory->id,
            'is_active' => 'boolean',
        ]);
        $subCategory->update($data);
        return new SubCategoryResource($subCategory);  // Updated: Use Resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return response()->json(['message' => 'SubCategory deleted']);  // No Resource needed
    }
}