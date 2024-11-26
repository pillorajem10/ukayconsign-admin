<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Promos;
use App\Models\Tally;
use App\Models\Store;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Billing;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Require authentication for all methods in this controller
    }

    public function index(Request $request)
    {
        $user = Auth::user(); // Fetch all user details
    
        // Fetch all stores for the authenticated user (no filtering based on store_id)
        $stores = Store::all(); // Get all stores without filtering
    
        // Initialize an array to hold earnings data
        $storeEarnings = [];
    
        // Calculate earnings for each store
        foreach ($stores as $store) {
            $storeId = $store->id;
            $storeEarnings[$storeId] = [
                'store_name' => $store->store_name, // Store name added
                'total_today' => Sale::where('sale_made', $storeId)
                    ->whereDate('createdAt', today())
                    ->sum('total'), 
                'total_month' => Sale::where('sale_made', $storeId)
                    ->whereMonth('createdAt', date('m'))
                    ->whereYear('createdAt', date('Y'))
                    ->sum('total'), 
            ];
        }        
    
        // Fetch all sales for the stores owned by the user (no filtering by store_id)
        $sales = Sale::whereIn('sale_made', $stores->pluck('id')->toArray())
            ->whereMonth('createdAt', date('m')) // Filter by the current month
            ->whereYear('createdAt', date('Y')) // Filter by the current year
            ->get(); // Retrieve the filtered sales
    
        // Fetch the latest 5 orders for the authenticated user
        $orders = Order::where('user_id', $user->id)
               ->orderBy('createdAt', 'desc') // Sort by the 'created_at' column in descending order
               ->take(5) // Limit to the 5 most recent orders
               ->get(); // Retrieve the orders

        // Initialize an array to count occurrences of each product
        $productCounts = [];
    
        foreach ($sales as $sale) {
            // Decode the ordered_items JSON string into an array
            $orderedItems = json_decode($sale->ordered_items, true);
            
            // Count the occurrences of each product_sku and map to product_bundle_id
            foreach ($orderedItems as $item) {
                $sku = $item['product_sku'];
                $bundleId = $item['product_bundle_id'];
                $quantity = $item['quantity']; // Get the quantity sold
    
                // Increment the total quantity for this SKU
                if (isset($productCounts[$sku])) {
                    $productCounts[$sku]['count'] += $quantity; // Add quantity to the existing count
                } else {
                    $productCounts[$sku] = [
                        'count' => $quantity, // Initialize count with the quantity
                        'product_bundle_id' => $bundleId,
                    ];
                }
            }
        }
    
        // Sort products by their total quantity sold in descending order and get the top products
        arsort($productCounts);
        $mostSoldProducts = array_slice($productCounts, 0, 5, true); // Get top 5 most sold products
    
        // Fetch the tally for yesterday
        $yesterday = Carbon::yesterday();
        $tallies = Tally::with('store')
            ->whereDate('createdAt', $yesterday)
            ->whereIn('store_id', $stores->pluck('id'))
            ->get(); // Retrieve tallies for yesterday
    
        // Fetch monthly data for all stores (no store filtering based on store_id)
        $monthlyData = [];
    
        // Fetch monthly totals for all stores
        $monthlyTotals = Sale::selectRaw('SUM(total) as total, MONTH(createdAt) as month')
            ->whereIn('sale_made', $stores->pluck('id')->toArray()) // Filter by all stores
            ->whereYear('createdAt', date('Y')) // Current year
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    
        // Create an array for all months
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyTotals->has($i) ? $monthlyTotals[$i]->total : 0;
        }
    
        // Fetch the latest 5 billing records for the authenticated user
        $billings = Billing::latest() // Order by latest first
            ->limit(5) // Limit to 5 records
            ->get(); 
    
    
        // Return the dashboard view with the user's details, stores, most sold products, orders, earnings, tallies, monthly data, and billings
        return view('pages.dashboard', compact(
            'user', 'stores', 'mostSoldProducts', 'orders', 'storeEarnings', 'tallies', 'monthlyData', 'billings'
        ));
    }                
}
