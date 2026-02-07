<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Featured products
        $featuredProducts = Product::with(['category', 'images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->take(8)
            ->get();

        // New arrivals
        $newArrivals = Product::with(['category', 'images'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Categories
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->take(6)
            ->get();

        // On sale products
        $saleProducts = Product::with(['category', 'images'])
            ->where('status', 'active')
            ->where('discount_type', '!=', 'none')
            ->where('discount_value', '>', 0)
            ->take(4)
            ->get();

        return view('frontend.home', compact('featuredProducts', 'newArrivals', 'categories', 'saleProducts'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('frontend.about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('frontend.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email or save to database

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
