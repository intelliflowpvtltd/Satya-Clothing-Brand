<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Show checkout page - redirects to address if not set.
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        // Check for default/selected address
        $selectedAddress = session('checkout_address_id')
            ? Address::find(session('checkout_address_id'))
            : Auth::user()->defaultAddress;

        if (!$selectedAddress) {
            return redirect()->route('checkout.address');
        }

        return redirect()->route('checkout.review');
    }

    /**
     * Show address selection page.
     */
    public function address()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();
        $selectedAddressId = session('checkout_address_id')
            ?? Auth::user()->defaultAddress?->id;

        return view('checkout.address', compact('addresses', 'selectedAddressId'));
    }

    /**
     * Save selected address.
     */
    public function saveAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        // Verify ownership
        $address = Address::where('user_id', Auth::id())
            ->findOrFail($request->address_id);

        session(['checkout_address_id' => $address->id]);

        return redirect()->route('checkout.review');
    }

    /**
     * Show order review page.
     */
    public function review()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $selectedAddressId = session('checkout_address_id')
            ?? Auth::user()->defaultAddress?->id;

        if (!$selectedAddressId) {
            return redirect()->route('checkout.address');
        }

        $address = Address::findOrFail($selectedAddressId);

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->is_on_sale
                ? $item->product->sale_price
                : $item->product->price);
        });

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

        return view('checkout.review', compact(
            'cartItems',
            'address',
            'subtotal',
            'discount',
            'total',
            'coupon'
        ));
    }

    /**
     * Place the order.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,upi,card,netbanking',
            'notes' => 'nullable|string|max:500',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->withErrors(['cart' => 'Your cart is empty.']);
        }

        $addressId = session('checkout_address_id')
            ?? Auth::user()->defaultAddress?->id;

        if (!$addressId) {
            return redirect()->route('checkout.address');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->product->is_on_sale
                    ? $item->product->sale_price
                    : $item->product->price);
            });

            $coupon = session('coupon');
            $discount = 0;
            $couponId = null;
            $couponCode = null;

            if ($coupon) {
                $couponId = $coupon['id'];
                $couponCode = $coupon['code'];
                if ($coupon['type'] === 'percentage') {
                    $discount = $subtotal * ($coupon['value'] / 100);
                } else {
                    $discount = min($coupon['value'], $subtotal);
                }
            }

            $codCharges = $request->payment_method === 'cod' ? 50 : 0;
            $total = $subtotal - $discount + $codCharges;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $addressId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'cod_charges' => $codCharges,
                'total_amount' => $total,
                'coupon_id' => $couponId,
                'coupon_code' => $couponCode,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $price = $item->product->is_on_sale
                    ? $item->product->sale_price
                    : $item->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_details' => "Size: {$item->variant->size}, Color: {$item->variant->color}",
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'total' => $price * $item->quantity,
                ]);

                // Reduce stock
                $item->variant->decrement('stock_quantity', $item->quantity);
            }

            // Record coupon usage
            if ($couponId) {
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                ]);

                // Increment times used
                \App\Models\Coupon::where('id', $couponId)->increment('times_used');
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            // Clear session data
            session()->forget(['coupon', 'checkout_address_id']);

            DB::commit();

            // Send order confirmation email
            $order->load(['items', 'address', 'user']);
            Mail::to(Auth::user())->send(new OrderConfirmationMail($order));

            return redirect()->route('checkout.complete', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['order' => 'Failed to place order. Please try again.']);
        }
    }

    /**
     * Show order complete page.
     */
    public function complete(Order $order)
    {
        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'address']);

        return view('checkout.complete', compact('order'));
    }

    /**
     * Get cart items for current user.
     */
    private function getCartItems()
    {
        return Cart::with(['product.images', 'variant'])
            ->where('user_id', Auth::id())
            ->get();
    }
}
