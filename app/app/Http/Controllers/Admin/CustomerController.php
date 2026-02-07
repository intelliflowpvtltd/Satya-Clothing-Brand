<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'reviews']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                    ->orWhere('email', 'ilike', '%' . $request->search . '%')
                    ->orWhere('mobile', 'ilike', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by newsletter
        if ($request->filled('newsletter')) {
            $query->where('newsletter_subscribed', $request->newsletter === 'yes');
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $customers = $query->paginate(20);

        // Stats
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'newsletter' => User::where('newsletter_subscribed', true)->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        $customer->load(['addresses', 'orders.items', 'reviews.product', 'wishlist.product']);

        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->where('payment_status', 'paid')->sum('total_amount'),
            'avg_order' => $customer->orders->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'newsletter_subscribed' => 'boolean',
        ]);

        $customer->update([
            'is_active' => $request->boolean('is_active'),
            'newsletter_subscribed' => $request->boolean('newsletter_subscribed'),
        ]);

        return back()->with('success', 'Customer updated successfully.');
    }
}
