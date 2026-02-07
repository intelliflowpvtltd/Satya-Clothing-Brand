<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('product', function ($pq) use ($request) {
                    $pq->where('name', 'ilike', '%' . $request->search . '%');
                })->orWhereHas('user', function ($uq) use ($request) {
                    $uq->where('name', 'ilike', '%' . $request->search . '%');
                });
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by verified
        if ($request->filled('verified')) {
            $query->where('is_verified_purchase', $request->verified === 'yes');
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
            'avg_rating' => round(Review::avg('rating'), 1) ?? 0,
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product.images']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'is_approved' => 'boolean',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $review->is_approved = $request->boolean('is_approved');

        if ($request->filled('admin_response')) {
            $review->admin_response = $request->admin_response;
            $review->admin_response_at = now();
        }

        $review->save();

        return back()->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        Review::whereIn('id', $ids)->update(['is_approved' => true]);

        return back()->with('success', count($ids) . ' reviews approved.');
    }
}
