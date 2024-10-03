<?php

namespace App\Http\Controllers;

use App\Models\ProductBarcode;
use Illuminate\Http\Request;

class ProductBarcodesController extends Controller
{
    public function index(Request $request)
    {
        $productSku = $request->input('product_sku'); // Get the product_sku input
    
        // Redirect if no SKU is provided
        if (!$productSku) {
            return redirect()->route('products.index')->with('error', 'No SKU found');
        }
    
        // Fetch barcodes with pagination
        $barcodes = ProductBarcode::where('product_sku', $productSku)->paginate(24);
    
        // Check if any barcodes are found
        if ($barcodes->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'No barcodes found');
        }
    
        return view('pages.productBarcodes', compact('barcodes', 'productSku')); // Pass data to the view
    }
      
}
