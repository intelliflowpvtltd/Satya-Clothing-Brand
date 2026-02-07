<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlist::with(['product.images', 'product.category', 'product.variants'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('account.wishlist', compact('wishlistItems'));
    }

    /**
     * Add product to wishlist.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Product already in wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    /**
     * Remove from wishlist.
     */
    public function remove($id)
    {
        $item = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $item->delete();

        return back()->with('success', 'Removed from wishlist.');
    }

    /**
     * Move item to cart.
     */
    public function moveToCart(Request $request, $id)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
        ]);

        $wishlistItem = Wishlist::where('user_id', Auth::id())->findOrFail($id);

        // Add to cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $wishlistItem->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $wishlistItem->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => 1,
            ]);
        }

        // Remove from wishlist
        $wishlistItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item moved to cart!');
    }

    /**
     * Toggle wishlist (for AJAX).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'added', 'message' => 'Added to wishlist']);
    }
}
