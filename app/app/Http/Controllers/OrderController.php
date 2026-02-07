<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product.images', 'address']);

        return view('account.orders.show', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return back()->withErrors(['order' => 'This order cannot be cancelled.']);
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock_quantity', $item->quantity);
            }
        }

        // Update order status
        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => $order->payment_status === 'paid' ? 'refunded' : 'cancelled',
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }
}
