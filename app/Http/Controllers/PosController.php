<?php

namespace App\Http\Controllers;

use App\Models\ProductBarcode;
use App\Models\PosReturnCart;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StoreInventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Mail\BadgePromotionMail;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    // Display the POS page and handle barcode search
    public function __construct()
    {
        $this->middleware('auth'); // Require authentication for all methods in this controller
    }

    public function index(Request $request)
    {
        $productDetails = null;
        $posCarts = null; // Initialize the variable for PosReturnCart list
        $selectedAction = $request->input('action', 'pos'); 
    
        // No store-related checks, no store ownership validation
    
        if ($request->isMethod('post')) {
            // Validate the request (removed store_id validation)
            $request->validate([
                'barcode_number' => 'required|string',
            ]);
    
            // Attempt to get product details based on the barcode (no store_id used)
            $result = $this->getProductDetails($request->barcode_number); // Removed store_id from the method call
    
            if ($result['error']) {
                return redirect()->route('pos.index')->with('error', $result['error']);
            }
    
            // Extract product details from the result
            $productDetails = $result['productDetails'];
    
            // If the selected action is "POS", add the product to the PosReturnCart
            if ($selectedAction === 'pos') {
                $barcode = ProductBarcode::where('barcode_number', $request->barcode_number)
                                         ->first();
                if ($barcode) {
                    if ($barcode->is_used) {
                        return redirect()->route('pos.index')->with('error', 'Barcode has already been used.');
                    }
                    
                    $barcode->is_used = true;
                    $barcode->save(); // Save the updated barcode
                } else {
                    return redirect()->route('pos.index')->with('error', 'Barcode not found.');
                }
    
                // Pass the barcode_number when calling addToPosCart
                $this->addToPosCart($productDetails, $request->barcode_number);
            }
        }
    
        // Retrieve all PosReturnCart list without any store_id filtering
        $posCarts = PosReturnCart::all(); // Now it fetches all PosReturnCart entries for all users
        
        return view('pages.pos', compact('productDetails', 'posCarts', 'selectedAction'));
    }

    private function getProductDetails($barcode)
    {
        // Search for the barcode number
        $barcodeDetails = ProductBarcode::where('barcode_number', $barcode)->first();
    
        // If barcode not found, return error
        if (!$barcodeDetails) {
            return ['error' => 'No items are detected on this barcode.'];
        }
    
        // Get the product using SKU from barcode
        $productDetails = Product::where('SKU', $barcodeDetails->product_sku)->first();
    
        // If product found, return product details
        if ($productDetails) {
            return [
                'error' => null,
                'productDetails' => $productDetails,
            ];
        }
    
        return ['error' => 'No items are detected on this barcode.'];
    }

    private function addToPosCart($productDetails, $barcodeNumber)
    {
        $existingCart = PosReturnCart::where('product_sku', $productDetails->SKU)
            ->first();
    
        if ($existingCart) {
            // If it exists, increment the quantity
            $existingCart->quantity += 1; // Increment by 1
            
            // Append the new barcode number to the barcode_numbers field
            $barcodeNumbers = json_decode($existingCart->barcode_numbers, true); // Decode the existing barcode numbers into an array
            $barcodeNumbers[] = $barcodeNumber; // Add the new barcode number
            $existingCart->barcode_numbers = json_encode($barcodeNumbers); // Encode back into JSON and save
    
            $existingCart->save(); // Save the updated cart
        } else {
            // If it doesn't exist, create a new PosReturnCart entry
            $posCart = new PosReturnCart();
            $posCart->product_sku = $productDetails->SKU;
            $posCart->quantity = 1; // Start with a quantity of 1
            $posCart->date_added = now();
            $posCart->product_bundle_id = $productDetails->ProductID;
    
            // Initialize barcode_numbers as an array with the first barcode number
            $posCart->barcode_numbers = json_encode([$barcodeNumber]);
    
            $posCart->save(); // Save the new cart entry
        }
    }

    public function voidItem(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_sku' => 'required|string',
        ]);
    
        $userId = auth()->id();
        $productSku = $request->input('product_sku');
    
        // Find the item in the PosReturnCart
        $posCartItem = PosReturnCart::where('product_sku', $productSku)
            ->first();
    
        if (!$posCartItem) {
            return redirect()->route('pos.index')
                ->with('error', 'Item not found in the cart.');
        }
    
        // Loop through the barcode_numbers and set is_used to false for each barcode
        $barcodeNumbers = json_decode($posCartItem->barcode_numbers, true); // Decode the barcode_numbers array
        foreach ($barcodeNumbers as $barcodeNumber) {
            $barcode = ProductBarcode::where('barcode_number', $barcodeNumber)->first();
            
            if ($barcode) {
                $barcode->is_used = false; // Set is_used to false
                $barcode->save(); // Save the updated barcode
            }
        }
    
        // Remove the item from the PosReturnCart
        $posCartItem->delete();
    
        return redirect()->route('pos.index')
            ->with('success', 'Item voided successfully.');
    }

    public function completeTransfer(Request $request)
    {
        // Retrieve all items from the PosReturnCart
        $posCarts = PosReturnCart::all();

        if ($posCarts->isEmpty()) {
            return redirect()->route('pos.index')->with('error', 'No items to transfer.');
        }

        foreach ($posCarts as $cart) {
            // Find or create the inventory item based on SKU
            $storeInventory = StoreInventory::where('SKU', $cart->product_sku)->first();

            if ($storeInventory) {
                // Update the stock for existing inventory
                $storeInventory->Stocks += $cart->quantity;
                $storeInventory->save();
            } else {
                // Create a new inventory entry
                StoreInventory::create([
                    'SKU' => $cart->product_sku,
                    'ProductID' => $cart->product_bundle_id,
                    'Stocks' => $cart->quantity,
                    'Consign' => 0, // Default value
                    'SPR' => 0,     // Default value
                    'store_id' => 7, // Set this based on your store logic
                ]);
            }

            // Delete the item from the PosReturnCart
            $cart->delete();
        }

        return redirect()->route('pos.index')->with('success', 'Transfer completed successfully.');
    }
}
