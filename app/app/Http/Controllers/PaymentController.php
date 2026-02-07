<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Create Razorpay order.
     */
    public function create(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'order_notes' => 'nullable|string|max:500',
        ]);

        // Verify address belongs to authenticated user
        $address = \App\Models\Address::where('user_id', Auth::id())
            ->find($request->address_id);

        if (!$address) {
            return response()->json(['error' => 'Invalid address'], 400);
        }

        // Get cart items
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $cart) {
            $price = $cart->product->is_on_sale ? $cart->product->sale_price : $cart->product->price;
            $subtotal += $price * $cart->quantity;
        }

        // Apply coupon if exists
        $discount = 0;
        $couponCode = session('coupon_code');
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $discount = $coupon->discount_type === 'percent'
                    ? ($subtotal * $coupon->discount_value / 100)
                    : $coupon->discount_value;
                if ($coupon->max_discount && $discount > $coupon->max_discount) {
                    $discount = $coupon->max_discount;
                }
            }
        }

        $totalAmount = $subtotal - $discount;

        // Create Razorpay order
        $razorpayKeyId = config('services.razorpay.key_id');
        $razorpayKeySecret = config('services.razorpay.key_secret');

        if (!$razorpayKeyId || !$razorpayKeySecret) {
            return response()->json(['error' => 'Payment gateway not configured'], 500);
        }

        try {
            $api = new \Razorpay\Api\Api($razorpayKeyId, $razorpayKeySecret);

            $razorpayOrder = $api->order->create([
                'receipt' => 'order_' . time(),
                'amount' => $totalAmount * 100, // Amount in paise
                'currency' => 'INR',
                'notes' => [
                    'user_id' => Auth::id(),
                    'address_id' => $request->address_id,
                ]
            ]);

            // Store order data in session
            session([
                'razorpay_order_id' => $razorpayOrder['id'],
                'payment_address_id' => $request->address_id,
                'payment_order_notes' => $request->order_notes,
            ]);

            return response()->json([
                'order_id' => $razorpayOrder['id'],
                'amount' => $totalAmount * 100,
                'currency' => 'INR',
                'key' => $razorpayKeyId,
                'name' => config('app.name'),
                'description' => 'Order Payment',
                'prefill' => [
                    'name' => Auth::user()->display_name,
                    'email' => Auth::user()->email,
                    'contact' => Auth::user()->mobile,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment initiation failed'], 500);
        }
    }

    /**
     * Verify Razorpay payment.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $razorpayKeySecret = config('services.razorpay.key_secret');

        // Verify signature
        $generatedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            $razorpayKeySecret
        );

        if (hash_equals($generatedSignature, $request->razorpay_signature)) {
            // Payment verified, create order
            $order = $this->createOrder(
                session('payment_address_id'),
                session('payment_order_notes'),
                'online',
                $request->razorpay_payment_id,
                $request->razorpay_order_id
            );

            if ($order) {
                // Clear session
                session()->forget(['razorpay_order_id', 'payment_address_id', 'payment_order_notes', 'coupon_code']);

                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.complete', $order)
                ]);
            }

            return response()->json(['error' => 'Order creation failed'], 500);
        }

        return response()->json(['error' => 'Payment verification failed'], 400);
    }

    /**
     * Handle Razorpay webhook.
     */
    public function webhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');

        // Verify webhook signature
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (!hash_equals($expectedSignature, $webhookSignature ?? '')) {
            Log::warning('Razorpay webhook signature mismatch');
            return response('Unauthorized', 401);
        }

        $event = json_decode($payload, true);

        switch ($event['event']) {
            case 'payment.captured':
                $this->handlePaymentCaptured($event['payload']['payment']['entity']);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($event['payload']['payment']['entity']);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Create order after successful payment.
     */
    private function createOrder($addressId, $orderNotes, $paymentMethod, $paymentId = null, $razorpayOrderId = null)
    {
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return null;
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $cart) {
            $price = $cart->product->is_on_sale ? $cart->product->sale_price : $cart->product->price;
            $subtotal += $price * $cart->quantity;
        }

        // Apply coupon
        $discount = 0;
        $couponCode = session('coupon_code');
        $coupon = null;
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $discount = $coupon->discount_type === 'percent'
                    ? ($subtotal * $coupon->discount_value / 100)
                    : $coupon->discount_value;
                if ($coupon->max_discount && $discount > $coupon->max_discount) {
                    $discount = $coupon->max_discount;
                }
            }
        }

        // Calculate GST (9% CGST + 9% SGST)
        $taxableAmount = $subtotal - $discount;
        $cgst = $taxableAmount * 0.09;
        $sgst = $taxableAmount * 0.09;

        $totalAmount = $taxableAmount + $cgst + $sgst;

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $addressId,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'shipping_charges' => 0,
            'cod_charges' => 0,
            'total_amount' => $totalAmount,
            'coupon_code' => $couponCode,
            'payment_method' => $paymentMethod,
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'order_notes' => $orderNotes,
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => $razorpayOrderId,
        ]);

        // Create order items and reduce stock
        foreach ($cartItems as $cart) {
            $price = $cart->product->is_on_sale ? $cart->product->sale_price : $cart->product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'product_name' => $cart->product->name,
                'variant_details' => $cart->variant ? "{$cart->variant->size} / {$cart->variant->color}" : '',
                'quantity' => $cart->quantity,
                'price' => $price,
                'total' => $price * $cart->quantity,
            ]);

            // Reduce stock
            if ($cart->variant) {
                $cart->variant->decrement('stock_quantity', $cart->quantity);
            }
        }

        // Track coupon usage
        if ($coupon) {
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => Auth::id(),
                'order_id' => $order->id,
            ]);
        }

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        return $order;
    }

    /**
     * Handle captured payment webhook.
     */
    private function handlePaymentCaptured($payment)
    {
        $order = Order::where('razorpay_payment_id', $payment['id'])->first();
        if ($order && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'confirmed',
            ]);
        }
    }

    /**
     * Handle failed payment webhook.
     */
    private function handlePaymentFailed($payment)
    {
        Log::info('Payment failed: ' . $payment['id']);
    }
}
