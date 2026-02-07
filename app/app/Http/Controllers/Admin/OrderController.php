<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'ilike', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'ilike', '%' . $request->search . '%')
                            ->orWhere('email', 'ilike', '%' . $request->search . '%');
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $orders = $query->paginate(20);

        // Summary stats
        $stats = [
            'pending' => Order::where('order_status', 'pending')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'coupon', 'address']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'nullable|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $changes = [];

        // Update order status
        if ($request->filled('order_status') && $order->order_status !== $request->order_status) {
            $oldStatus = $order->order_status;
            $order->order_status = $request->order_status;
            $changes[] = "Status changed from '{$oldStatus}' to '{$request->order_status}'";

            // Auto-update timestamps based on status
            if ($request->order_status === 'shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            } elseif ($request->order_status === 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }
        }

        // Update payment status
        if ($request->filled('payment_status') && $order->payment_status !== $request->payment_status) {
            $oldPayment = $order->payment_status;
            $order->payment_status = $request->payment_status;
            $changes[] = "Payment status changed from '{$oldPayment}' to '{$request->payment_status}'";

            if ($request->payment_status === 'paid' && !$order->paid_at) {
                $order->paid_at = now();
            }
        }

        // Update tracking info
        if ($request->filled('tracking_number')) {
            $order->tracking_number = $request->tracking_number;
        }
        if ($request->filled('tracking_url')) {
            $order->tracking_url = $request->tracking_url;
        }

        // Save admin notes (append to existing)
        if ($request->filled('admin_notes')) {
            $timestamp = now()->format('Y-m-d H:i');
            $adminName = auth('admin')->user()->name;
            $newNote = "[{$timestamp}] {$adminName}: {$request->admin_notes}";
            $order->admin_notes = $order->admin_notes
                ? $order->admin_notes . "\n\n" . $newNote
                : $newNote;
        }

        $order->save();

        $message = count($changes) > 0
            ? 'Order updated: ' . implode(', ', $changes)
            : 'Order updated successfully.';

        return back()->with('success', $message);
    }

    /**
     * Quick update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,out_for_delivery,delivered,cancelled,returned',
        ]);

        $order->update(['order_status' => $validated['status']]);

        return back()->with('success', 'Order status updated to ' . ucfirst($validated['status']));
    }
}
