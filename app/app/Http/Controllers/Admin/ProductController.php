<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage', 'variants']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                    ->orWhere('sku', 'ilike', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->whereHas('variants', function ($q) {
                    $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                        ->where('stock_quantity', '>', 0);
                });
            } elseif ($request->stock === 'out') {
                $query->whereHas('variants', function ($q) {
                    $q->where('stock_quantity', 0);
                });
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate(15);
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'fabric' => 'nullable|string|max:100',
            'care_instructions' => 'nullable|string',
            'gender' => 'required|in:men,women,unisex,kids',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'status' => 'required|in:active,inactive,draft',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.color_code' => 'nullable|string|max:7',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Generate unique SKU
            $sku = 'PRD-' . strtoupper(Str::random(8));

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'sku' => $sku,
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'price' => $validated['price'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'] ?? 0,
                'fabric' => $validated['fabric'] ?? null,
                'care_instructions' => $validated['care_instructions'] ?? null,
                'gender' => $validated['gender'],
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'is_new_arrival' => $request->boolean('is_new_arrival'),
                'status' => $validated['status'],
            ]);

            // Upload images
            $displayOrder = 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/' . $product->id, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'display_order' => $displayOrder++,
                    'is_primary' => $index === 0,
                ]);
            }

            // Create variants
            foreach ($validated['variants'] as $variantData) {
                $variantSku = $sku . '-' . strtoupper(substr($variantData['size'], 0, 2)) . '-' . strtoupper(substr($variantData['color'], 0, 3));
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variantData['size'],
                    'color' => $variantData['color'],
                    'color_code' => $variantData['color_code'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                    'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                    'variant_sku' => $variantSku,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants', 'reviews']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['images', 'variants']);
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'fabric' => 'nullable|string|max:100',
            'care_instructions' => 'nullable|string',
            'gender' => 'required|in:men,women,unisex,kids',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'status' => 'required|in:active,inactive,draft',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.color_code' => 'nullable|string|max:7',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Update product
            $product->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'price' => $validated['price'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'] ?? 0,
                'fabric' => $validated['fabric'] ?? null,
                'care_instructions' => $validated['care_instructions'] ?? null,
                'gender' => $validated['gender'],
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'is_new_arrival' => $request->boolean('is_new_arrival'),
                'status' => $validated['status'],
            ]);

            // Delete selected images
            if ($request->filled('delete_images')) {
                $imagesToDelete = ProductImage::whereIn('id', $request->delete_images)->get();
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Upload new images
            if ($request->hasFile('new_images')) {
                $maxOrder = $product->images()->max('display_order') ?? -1;
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('products/' . $product->id, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $product->name,
                        'display_order' => ++$maxOrder,
                        'is_primary' => $product->images()->count() === 0,
                    ]);
                }
            }

            // Update variants
            $existingVariantIds = [];
            foreach ($validated['variants'] as $variantData) {
                if (!empty($variantData['id'])) {
                    // Update existing variant
                    $variant = ProductVariant::find($variantData['id']);
                    $variant->update([
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'color_code' => $variantData['color_code'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                    ]);
                    $existingVariantIds[] = $variant->id;
                } else {
                    // Create new variant
                    $variantSku = $product->sku . '-' . strtoupper(substr($variantData['size'], 0, 2)) . '-' . strtoupper(substr($variantData['color'], 0, 3));
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'color_code' => $variantData['color_code'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'low_stock_threshold' => $variantData['low_stock_threshold'] ?? 5,
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                        'variant_sku' => $variantSku,
                    ]);
                    $existingVariantIds[] = $variant->id;
                }
            }

            // Delete variants not in the update
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete all images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete product (cascades to images and variants via DB)
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Remove primary from all images
        $product->images()->update(['is_primary' => false]);

        // Set new primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }
}
