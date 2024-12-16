<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Store;
use App\Models\Product;
use App\Models\ReceivedProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaleBreakdownController extends Controller
{
    /**
     * Display the sale breakdown page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the store ID and filter from the request
        $store_id = $request->get('store_id');
        $filter = $request->get('filter', 'daily'); // Default to 'daily' if no filter is provided
    
        // Check if the authenticated user is the owner of the store
        $store = Store::where('id', $store_id)
            ->first();
    
        // If no store is found or the authenticated user is not the owner, redirect with an error
        if (!$store) {
            return redirect()->route('dashboard')->with('error', 'You don\'t have authority to access this sales list');
        }
    
        // Initialize the query for sales data based on the store ID
        $salesQuery = Sale::where('sale_made', $store_id);
    
        // Filter the sales query based on the selected filter
        if ($filter == 'daily') {
            // Filter sales by the current day (ignore time, compare only date part)
            $day = $request->get('day');
            $salesQuery->whereDate('createdAt', '=', $day);  // Compare only the date part (YYYY-MM-DD)
        } elseif ($filter == 'monthly') {
            // Filter sales by the selected month (ignore time, compare only the month and year)
            $month = $request->get('month');
            $year = $request->get('year', date('Y'));  // Default to the current year if no year is passed
            $salesQuery->whereMonth('createdAt', '=', $month)
                ->whereYear('createdAt', '=', $year);
        } elseif ($filter == 'yearly') {
            // Filter sales by the selected year
            $year = $request->get('year');
            $salesQuery->whereYear('createdAt', '=', $year);
        }
    
        // Execute the sales query to get the filtered sales data
        $sales = $salesQuery->get();
    
        // Initialize an array to store breakdown data
        $breakdown = [];
    
        // Loop through each sale and decode the ordered_items JSON
        foreach ($sales as $sale) {
            $orderedItems = json_decode($sale->ordered_items, true); // Decode the JSON
    
            // Loop through each item in the ordered_items and aggregate by product_bundle_id
            foreach ($orderedItems as $item) {
                $productBundleId = $item['product_bundle_id'];
                $subTotal = (float) $item['sub_total'];
    
                // If the product_bundle_id is already in the breakdown, add the sub_total
                if (isset($breakdown[$productBundleId])) {
                    $breakdown[$productBundleId] += $subTotal;
                } else {
                    // Otherwise, initialize the sum with the current sub_total
                    $breakdown[$productBundleId] = $subTotal;
                }
            }
        }
    
        // Pass the breakdown data and store_id to the view
        return view('pages.saleBreakdown', [
            'breakdown' => $breakdown,
            'filter' => $filter,
            'store_id' => $store_id,
        ]);
    }    
    
    public function qtySoldItems()
    {
        // Retrieve all products
        $products = Product::all();
    
        // Retrieve all stores
        $stores = Store::all();
    
        // Initialize an array to store the aggregated data
        $data = [];
    
        foreach ($products as $product) {
            // Get the highest cost for the current product SKU from the ReceivedProduct table
            $highestCost = ReceivedProduct::where('product_sku', $product->SKU)->max('cost');
    
            $row = [
                'product_name' => $product->ProductID,
                'consign' => $product->Consign, // Assuming 'Consign' is a property in your Product model
                'highest_cost' => $highestCost ?? 0, // Default to 0 if no cost data is available
            ];
    
            foreach ($stores as $store) {
                // Calculate the total quantity of this product sold in this store
                $quantitySold = Sale::where('sale_made', $store->id)
                    ->get()
                    ->flatMap(function ($sale) {
                        return json_decode($sale->ordered_items, true);
                    })
                    ->filter(function ($item) use ($product) {
                        return $item['product_sku'] === $product->SKU;
                    })
                    ->sum('quantity');
    
                // Store the quantity in the array
                $row[$store->store_name] = $quantitySold ?? 0; // Default to 0 if no data
            }
    
            $data[] = $row;
        }
    
        return view('pages.qtySold', compact('data', 'stores'));
    }
      
}
