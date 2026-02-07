<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the shop page with filters.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants'])
            ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('sku', 'ilike', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)
                    ->orWhereHas('parent', function ($pq) use ($request) {
                        $pq->where('slug', $request->category);
                    });
            });
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price)
                    ->orWhere('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Size filter
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size', $request->size)->where('stock_quantity', '>', 0);
            });
        }

        // Color filter
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color', $request->color)->where('stock_quantity', '>', 0);
            });
        }

        // Sale filter
        if ($request->filled('sale') && $request->sale) {
            $query->where('discount_type', '!=', 'none')->where('discount_value', '>', 0);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        // Get categories for filter
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->withCount('products')
            ->get();

        // Get unique sizes and colors for filters
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $colors = ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Pink', 'Purple', 'Orange', 'Brown', 'Grey', 'Navy'];

        return view('frontend.shop.index', compact('products', 'categories', 'sizes', 'colors'));
    }

    /**
     * Display a single product.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Related products
        $relatedProducts = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Approved reviews
        $reviews = $product->reviews()
            ->where('is_approved', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $avgRating = $reviews->avg('rating') ?? 0;

        return view('frontend.shop.show', compact('product', 'relatedProducts', 'reviews', 'avgRating'));
    }

    /**
     * Display products by category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::with(['category', 'images'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        return view('frontend.shop.category', compact('category', 'products', 'categories'));
    }
}
