<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cartItems = Cart::with(['product.images', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->is_on_sale
                ? $item->product->sale_price
                : $item->product->price);
        });

        // Get coupon from session if applied
        $coupon = session('coupon');
        $discount = 0;

        if ($coupon) {
            if ($coupon['type'] === 'percentage') {
                $discount = $subtotal * ($coupon['value'] / 100);
            } else {
                $discount = min($coupon['value'], $subtotal);
            }
        }

        $total = $subtotal - $discount;

        return view('cart.index', compact('cartItems', 'subtotal', 'discount', 'total', 'coupon'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        // Check stock
        if ($variant->stock_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        // Check if item already exists in cart
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;

            if ($newQuantity > $variant->stock_quantity) {
                return back()->withErrors(['quantity' => 'Cannot add more items. Stock limit reached.']);
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);

        // Check stock
        if ($cartItem->variant->stock_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Apply coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return back()->withErrors(['code' => 'Invalid coupon code.']);
        }

        // Check validity dates
        if ($coupon->valid_from && $coupon->valid_from->isFuture()) {
            return back()->withErrors(['code' => 'This coupon is not yet active.']);
        }

        if ($coupon->valid_to && $coupon->valid_to->isPast()) {
            return back()->withErrors(['code' => 'This coupon has expired.']);
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit) {
            return back()->withErrors(['code' => 'This coupon has reached its usage limit.']);
        }

        // Check user usage limit
        if ($coupon->usage_per_customer) {
            $userUsage = $coupon->usages()->where('user_id', Auth::id())->count();
            if ($userUsage >= $coupon->usage_per_customer) {
                return back()->withErrors(['code' => 'You have already used this coupon.']);
            }
        }

        // Check minimum order
        $cartTotal = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->product->is_on_sale
                    ? $item->product->sale_price
                    : $item->product->price);
            });

        if ($coupon->min_order_amount && $cartTotal < $coupon->min_order_amount) {
            return back()->withErrors([
                'code' => "Minimum order of ₹{$coupon->min_order_amount} required for this coupon."
            ]);
        }

        // Store coupon in session
        session(['coupon' => [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->discount_type,
            'value' => $coupon->discount_value,
        ]]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }
}
