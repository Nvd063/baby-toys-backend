<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class AdminProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['subCategory.category'])->paginate(10); // Yeh paginate(10) zaroori hai
        return view('admin.products.index', compact('products'));
    }
    // Step 1: Category select/custom
    public function step1()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.products.create.step1', compact('categories'));
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'custom_category' => 'required_without:category_id|string|max:191',
        ]);

        $categoryId = $request->category_id;
        if (!$categoryId && $request->custom_category) {
            $category = Category::create([
                'name' => $request->custom_category,
                'slug' => Str::slug($request->custom_category),
                'is_active' => 1,
            ]);
            $categoryId = $category->id;
        }

        session(['create_product.category_id' => $categoryId]);

        return redirect()->route('admin.products.create.step2');
    }

    // Step 2: Subcategory select/custom
    public function step2()
    {
        $categoryId = session('create_product.category_id');
        if (!$categoryId) {
            return redirect()->route('admin.products.create.step1');
        }

        $subCategories = SubCategory::where('category_id', $categoryId)->where('is_active', 1)->get();
        return view('admin.products.create.step2', compact('subCategories'));
    }

    public function postStep2(Request $request)
    {
        $categoryId = session('create_product.category_id');
        if (!$categoryId) {
            return redirect()->route('admin.products.create.step1');
        }

        $request->validate([
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'custom_sub_category' => 'required_without:sub_category_id|string|max:191',
        ]);

        $subCategoryId = $request->sub_category_id;
        if (!$subCategoryId && $request->custom_sub_category) {
            $subCategory = SubCategory::create([
                'category_id' => $categoryId,
                'name' => $request->custom_sub_category,
                'slug' => Str::slug($request->custom_sub_category),
                'is_active' => 1,
            ]);
            $subCategoryId = $subCategory->id;
        }

        session(['create_product.sub_category_id' => $subCategoryId]);

        return redirect()->route('admin.products.create.step3');
    }

    // Step 3: Product details
    public function step3()
    {
        $subCategoryId = session('create_product.sub_category_id');
        if (!$subCategoryId) {
            return redirect()->route('admin.products.create.step1');
        }

        $subCategory = SubCategory::findOrFail($subCategoryId);
        return view('admin.products.create.step3', compact('subCategory'));
    }

    public function postStep3(Request $request)
    {
        $subCategoryId = session('create_product.sub_category_id');

        if (!$subCategoryId) {
            return redirect()->route('admin.products.create.step1');
        }

        $request->validate([
            'name' => 'required|string|max:191',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');

                // Directory banao agar nahi hai (public/products)
                $productsDir = 'public/products';
                if (!Storage::exists($productsDir)) {
                    Storage::makeDirectory($productsDir);
                }

                $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs($productsDir, $imageName); // Save karega
                $imagePath = str_replace('public/', '', $imagePath); // DB mein 'products/filename.jpg'

                // Log for check (storage/logs/laravel.log mein dekho success/error)
                \Log::info('Image saved: ' . $imagePath);

            } catch (\Exception $e) {
                \Log::error('Image save failed: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Upload failed: ' . $e->getMessage()])->withInput();
            }
        }

        Product::create([
            'sub_category_id' => $subCategoryId,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => 1,
        ]);

        session()->forget('create_product');

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }
}