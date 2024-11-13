<?php

namespace App\Http\Controllers;

use App\Models\UscReturn;
use App\Models\StoreInventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UscReturnController extends Controller
{
    // Index method to list all return requests
    public function index(Request $request)
    {
        // Ensure the user is authenticated
        $authId = Auth::id();
    
        // Fetch all return requests, paginated (limit 10), without filtering by store_id
        $returns = UscReturn::with(['user', 'store', 'product'])
                            ->paginate(10);  // 10 items per page
    
        // Pass the returns data to the view
        return view('pages.returnRequestList', compact('returns'));
    }

    // Method to update the status of a return request
    public function updateStatus(Request $request)
    {
        // Validate the request data to ensure the return ID exists and the return status is valid
        $validated = $request->validate([
            'return_id' => 'required|exists:usc_returns,id',  // Ensure the return exists in the 'usc_returns' table
            'return_status' => 'required|in:Processing,Shipped,Received,Packed Back To Store,Shipped Back To Store,Received By Store',  // Ensure the status is one of the valid options
        ]);        
    
        // Find the return request from the database
        $return = UscReturn::find($validated['return_id']);
        $oldStatus = $return->return_status;  // Store the old status (if needed for further logic or audit)
    
        // Update the return status with the new value
        $return->return_status = $validated['return_status'];
        $return->save();  // Save the updated status to the database
    
        // If the status is updated to "Shipped", we update the inventory
        if ($return->return_status == 'Shipped') {
            // Get the store_id, product_sku (from the return), and quantity to subtract
            $storeId = $return->store_id;
            $productSku = $return->product_sku;
            $quantityToSubtract = $return->quantity;
    
            // Find the corresponding inventory record based on store_id and product_sku
            $inventory = StoreInventory::where('store_id', $storeId)
                                        ->where('SKU', $productSku)  // Assuming the inventory table uses 'SKU' field for product identifier
                                        ->first();
    
            // If an inventory record is found, subtract the return quantity from the stock
            if ($inventory) {
                $inventory->Stocks -= $quantityToSubtract;
                $inventory->save();  // Save the updated inventory stock
            } else {
                // If no inventory record is found, return an error message
                return redirect()->route('usc-returns.index')->with('error', 'Inventory record not found.');
            }
        }
    
        // If the status is updated to "Received", we add the return quantity to the product stock
        if ($return->return_status == 'Received') {
            // Get the product SKU and the quantity to add
            $productSku = $return->product_sku;
            $quantityToAdd = $return->quantity;
    
            // Find the product record using the product SKU
            $product = Product::where('SKU', $productSku)->first();
    
            // If the product exists, add the quantity to the Stock
            if ($product) {
                $product->Stock += $quantityToAdd;
                $product->save();  // Save the updated product stock
            } else {
                // If no product record is found, return an error message
                return redirect()->route('usc-returns.index')->with('error', 'Product not found.');
            }
        }

        if ($return->return_status == 'Shipped Back To Store') {
            // Get the product SKU and the quantity to add
            $productSku = $return->product_sku;
            $quantityToSub = $return->quantity;
    
            // Find the product record using the product SKU
            $product = Product::where('SKU', $productSku)->first();
    
            // If the product exists, add the quantity to the Stock
            if ($product) {
                $product->Stock -= $quantityToSub;
                $product->save();  // Save the updated product stock
            } else {
                // If no product record is found, return an error message
                return redirect()->route('usc-returns.index')->with('error', 'Product not found.');
            }
        }

        if ($return->return_status == 'Received By Store') {
            // Get the store_id, product_sku (from the return), and quantity to subtract
            $storeId = $return->store_id;
            $productSku = $return->product_sku;
            $quantityToAdd = $return->quantity;
    
            // Find the corresponding inventory record based on store_id and product_sku
            $inventory = StoreInventory::where('store_id', $storeId)
                                        ->where('SKU', $productSku)  // Assuming the inventory table uses 'SKU' field for product identifier
                                        ->first();
    
            // If an inventory record is found, subtract the return quantity from the stock
            if ($inventory) {
                $inventory->Stocks += $quantityToAdd;
                $inventory->save();  // Save the updated inventory stock
            } else {
                // If no inventory record is found, return an error message
                return redirect()->route('usc-returns.index')->with('error', 'Inventory record not found.');
            }
        }
    
        // After the update, redirect back to the return request list with a success message
        return redirect()->route('usc-returns.index')->with('success', 'Return request status updated successfully.');
    }
}
