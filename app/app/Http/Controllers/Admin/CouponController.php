<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Coupon::withCount('usages');

        // Search
        if ($request->filled('search')) {
            $query->where('code', 'ilike', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->where('valid_to', '<', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('discount_type', $request->type);
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.coupons.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_customer' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'applicable_categories' => 'nullable|array',
            'applicable_products' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['usages.user', 'usages.order']);
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $categories = Category::active()->get();
        return view('admin.coupons.edit', compact('coupon', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_customer' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'applicable_categories' => 'nullable|array',
            'applicable_products' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        // Check if coupon has usages
        if ($coupon->usage_count > 0) {
            return back()->with('error', 'Cannot delete coupon that has been used. Consider deactivating it instead.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    /**
     * Generate a random coupon code
     */
    public function generateCode()
    {
        $code = strtoupper(Str::random(8));

        // Ensure uniqueness
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }

        return response()->json(['code' => $code]);
    }
}
