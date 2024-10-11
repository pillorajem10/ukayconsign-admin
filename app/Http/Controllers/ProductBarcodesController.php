<?php

namespace App\Http\Controllers;

use App\Models\ProductBarcode;
use Illuminate\Http\Request;

class ProductBarcodesController extends Controller
{
    public function index(Request $request)
    {
        $productSku = $request->input('product_sku'); // Get the product_sku input
        $receivedProductId = $request->input('received_product_id'); // Get the received_product_id input

        // Redirect if either SKU or received_product_id is not provided
        if (empty($productSku) || empty($receivedProductId)) {
            return redirect()->route('products.index')->with('error', 'Both SKU and received product ID must be provided');
        }

        // Fetch barcodes with pagination
        $barcodesQuery = ProductBarcode::where('received_product_id', $receivedProductId);

        // Show all if is_used is false, otherwise filter by product_sku
        if ($request->input('is_used') === 'false') {
            // Only apply received_product_id filter
            $barcodes = $barcodesQuery->paginate(24);
        } else {
            // Filter by product_sku as well
            $barcodes = $barcodesQuery->where('product_sku', $productSku)->paginate(24);
        }

        // Check if any barcodes are found
        if ($barcodes->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'No barcodes found');
        }

        return view('pages.productBarcodes', compact('barcodes', 'productSku', 'receivedProductId')); // Pass data to the view
    }       
}
