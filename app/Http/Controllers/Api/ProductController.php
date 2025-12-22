<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'published');
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return ProductListResource::collection($products);
    }

    /**
     * Display the specified product
     */
    public function show($slug)
    {
        $product = Product::with('category', 'creator', 'updater')
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }

    /**
     * Store a newly created product (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:2000',
            'slug' => 'nullable|string|max:2000|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:draft,published,out_of_stock,discontinued',
            'is_featured' => 'boolean',
            'token_reward_on_purchase' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ← NEW: Image validation
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Generate unique filename
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();

            // Store in public/storage/products
            $path = $image->storeAs('products', $filename, 'public');

            $validated['image'] = $path;
            $validated['image_mime'] = $image->getMimeType();
            $validated['image_size'] = $image->getSize();
        }

        $product = Product::create($validated);

        return new ProductResource($product);
    }

    /**
     * Update the specified product (Admin only)
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:2000',
            'slug' => 'nullable|string|max:2000|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|in:draft,published,out_of_stock,discontinued',
            'is_featured' => 'boolean',
            'token_reward_on_purchase' => 'sometimes|required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ← NEW
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated['updated_by'] = $user->id;

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');

            $validated['image'] = $path;
            $validated['image_mime'] = $image->getMimeType();
            $validated['image_size'] = $image->getSize();
        }

        $product->update($validated);

        return new ProductResource($product);
    }

    /**
     * Remove the specified product (Admin only)
     */
    public function destroy(Product $product)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->update(['deleted_by' => $user->id]);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
