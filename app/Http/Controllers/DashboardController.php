<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $businessId = $user->business_id;

        // 1. Cache aggregated financial statistics for 1 hour
        $stats = Cache::remember("business.{$businessId}.dashboard_stats", 3600, function () use ($businessId) {
            return [
                'total_invoices' => Invoice::where('business_id', $businessId)->count(),
                'total_revenue'  => Invoice::where('business_id', $businessId)->sum('grand_total'),
                'total_gst'      => Invoice::where('business_id', $businessId)->sum('total_gst'),
                'low_stock_count' => Product::where('business_id', $businessId)->lowStock()->count(),
            ];
        });

        // 2. Fetch the 5 most recent invoices
        $recentInvoices = Invoice::where('business_id', $businessId)
            ->with('customer')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats'          => $stats,
            'recentInvoices' => $recentInvoices,
            'business'       => $user->business,
        ]);
    }
}
