<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        $totalOrders = Order::get();
        $getListOrder5 = Order::orderBy('id', 'desc')->take(5)->get();
        $totalPrice = Order::where('payment_status', 'Completed')->sum('total_price');
        $totalProducts = Product::get();
        $totalCustomers = Order::where('payment_status', 'Completed')
                                ->distinct()
                                ->count('customer_id');

        // $productIds = DB::table('tbl_order_item')
        //             ->select('product_id', DB::raw('SUM(quantity) as Total'))
        //             ->groupBy('product_id')
        //             ->orderByDesc('Total')
        //             ->limit(5)
        //             ->pluck('product_id')->toArray();
        // $top5BestSellers = Product::whereIn('id', $productIds)->get();

        $getProductIDTop5BestSeller = DB::table('tbl_order_item')
                ->select('product_id', DB::raw('SUM(quantity) as Total'))
                ->groupBy('product_id')
                ->orderByDesc('Total')
                ->limit(5)
                ->get();
        $soldMap = $getProductIDTop5BestSeller->pluck('Total', 'product_id');
        $productIds = $soldMap->keys()->toArray();
        $top5BestSellers = Product::whereIn('id', $productIds)->get()->map(function ($product) use ($soldMap) {
            $product->quantity_sold = $soldMap[$product->id] ?? 0;
            return $product;
        });

        $salesData = DB::table('tbl_order')
                ->select(
                    DB::raw('MONTH(order_date) as month'),
                    DB::raw('SUM(total_price) as total')
                )
                ->where('payment_status', 'Completed')
                ->whereYear('order_date', date('Y'))
                ->groupBy(DB::raw('MONTH(order_date)'))
                ->orderBy('month')
                ->get();

        // Tạo mảng labels và data cho chart
        $labels = [];
        $data = [];

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        foreach ($salesData as $sale) {
            $labels[] = $months[$sale->month - 1];
            $data[] = (float) $sale->total;
        }

        return view('page.admin.dashboard', compact('getListOrder5', 'totalOrders', 'totalPrice', 'totalProducts', 'totalCustomers', 'labels', 'data', 'top5BestSellers'));
    }
}
