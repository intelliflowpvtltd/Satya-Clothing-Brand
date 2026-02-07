<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductReturn::with(['order', 'orderItem.product', 'user']);

        // Search
        if ($request->filled('search')) {
            $query->where('return_number', 'ilike', '%' . $request->search . '%')
                ->orWhereHas('order', function ($q) use ($request) {
                    $q->where('order_number', 'ilike', '%' . $request->search . '%');
                });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('return_reason', $request->reason);
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $stats = [
            'pending' => ProductReturn::where('status', 'pending')->count(),
            'approved' => ProductReturn::where('status', 'approved')->count(),
            'picked_up' => ProductReturn::whereIn('status', ['pickup_scheduled', 'picked_up', 'in_transit'])->count(),
            'completed' => ProductReturn::whereIn('status', ['refund_processed', 'completed'])->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReturn $return)
    {
        $return->load(['order.items', 'orderItem.product', 'orderItem.variant', 'user']);
        return view('admin.returns.show', compact('return'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReturn $return)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,approved,rejected,pickup_scheduled,picked_up,in_transit,received,qc_passed,qc_failed,refund_processing,refund_processed,completed',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:500',
            'refund_amount' => 'nullable|numeric|min:0',
            'pickup_scheduled_at' => 'nullable|date',
        ]);

        // Update status
        if ($request->filled('status')) {
            $return->status = $request->status;

            // Set timestamps based on status
            switch ($request->status) {
                case 'approved':
                    $return->approved_at = now();
                    break;
                case 'rejected':
                    $return->rejection_reason = $request->rejection_reason;
                    break;
                case 'pickup_scheduled':
                    $return->pickup_scheduled_at = $request->pickup_scheduled_at ?? now()->addDays(2);
                    break;
                case 'picked_up':
                    $return->picked_up_at = now();
                    break;
                case 'received':
                    $return->received_at = now();
                    break;
                case 'qc_passed':
                case 'qc_failed':
                    $return->qc_completed_at = now();
                    break;
                case 'refund_processed':
                    $return->refund_processed_at = now();
                    $return->refund_amount = $request->refund_amount ?? $return->orderItem->total_price;
                    break;
                case 'completed':
                    $return->completed_at = now();
                    break;
            }
        }

        // Append admin notes
        if ($request->filled('admin_notes')) {
            $timestamp = now()->format('Y-m-d H:i');
            $adminName = auth('admin')->user()->name;
            $newNote = "[{$timestamp}] {$adminName}: {$request->admin_notes}";
            $return->admin_notes = $return->admin_notes
                ? $return->admin_notes . "\n\n" . $newNote
                : $newNote;
        }

        $return->save();

        return back()->with('success', 'Return request updated successfully.');
    }
}
