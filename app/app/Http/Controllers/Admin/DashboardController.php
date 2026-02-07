<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Today's stats
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // This month's stats
        $monthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Pending orders
        $pendingOrders = Order::where('order_status', 'pending')->count();

        // Low stock products
        $lowStockProducts = ProductVariant::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->count();

        // Out of stock products
        $outOfStockProducts = ProductVariant::where('stock_quantity', 0)->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();

        // New customers this month
        $newCustomers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalCustomers = User::count();

        // Sales chart data (last 7 days)
        $salesChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Order status distribution
        $orderStatusStats = Order::select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status');

        // Top selling products
        $topProducts = Product::withCount(['variants as sold_count' => function ($query) {
            // This would need order_items relationship properly set up
        }])
            ->active()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'monthOrders',
            'monthRevenue',
            'pendingOrders',
            'lowStockProducts',
            'outOfStockProducts',
            'recentOrders',
            'newCustomers',
            'totalCustomers',
            'salesChart',
            'orderStatusStats',
            'topProducts'
        ));
    }
}
