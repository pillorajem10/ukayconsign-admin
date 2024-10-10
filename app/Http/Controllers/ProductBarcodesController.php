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
    
        // Fetch barcodes with pagination, filtered by product_sku and received_product_id
        $barcodes = ProductBarcode::where('product_sku', $productSku)
            ->where('received_product_id', $receivedProductId)
            ->paginate(24);
    
        // Check if any barcodes are found
        if ($barcodes->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'No barcodes found');
        }
    
        return view('pages.productBarcodes', compact('barcodes', 'productSku', 'receivedProductId')); // Pass data to the view
    }       
}
