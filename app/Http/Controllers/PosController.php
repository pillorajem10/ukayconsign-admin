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
        $barcodeDetails = null;  // Initialize variable for barcode details
        $posCarts = null; // Initialize the variable for PosReturnCart list
        $selectedAction = $request->input('action', 'pos'); 
    
        // No store-related checks, no store ownership validation
    
        if ($request->isMethod('post')) {
            // Validate the request (removed store_id validation)
            $request->validate([
                'barcode_number' => 'required|string',
            ]);
    
            // Attempt to get product details and barcode details based on the barcode
            $result = $this->getProductDetails($request->barcode_number); // Removed store_id from the method call
    
            if ($result['error']) {
                return redirect()->route('pos.index')->with('error', $result['error']);
            }
    
            // Extract product details and barcode details from the result
            $productDetails = $result['productDetails'];
            $barcodeDetails = $result['barcodeDetails'];  // Get the barcode details
    
            // If the selected action is "POS", add the product to the PosReturnCart
            if ($selectedAction === 'pos') {
                $barcode = ProductBarcode::where('barcode_number', $request->barcode_number)
                                         ->first();
                if (!$barcode) {
                    return redirect()->route('pos.index')->with('error', 'Barcode not found.');
                }
    
                // No need to update `is_used` field anymore
                $this->addToPosCart($productDetails, $request->barcode_number);
            }
        }
    
        // Retrieve all PosReturnCart list without any store_id filtering
        $posCarts = PosReturnCart::all(); // Now it fetches all PosReturnCart entries for all users
    
        return view('pages.pos', compact('productDetails', 'barcodeDetails', 'posCarts', 'selectedAction'));
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
    
        // If product found, return product details and barcode details
        if ($productDetails) {
            return [
                'error' => null,
                'productDetails' => $productDetails,
                'barcodeDetails' => $barcodeDetails, // Add barcodeDetails to the result
            ];
        }
    
        return ['error' => 'No items are detected on this barcode.'];
    }
    

    private function addToPosCart($productDetails, $barcodeNumber)
    {
        // Check if there's already a cart for the same product SKU
        $existingCart = PosReturnCart::where('product_sku', $productDetails->SKU)
            ->first();
    
        if ($existingCart) {
            // Decode the existing barcode_numbers into an array
            $barcodeNumbers = json_decode($existingCart->barcode_numbers, true); 
            
            // Check if the barcode number is already in the barcode_numbers array
            if (in_array($barcodeNumber, $barcodeNumbers)) {
                // If barcode already exists, return an error
                return redirect()->route('pos.index')->with('error', 'This barcode is already in the cart.');
            }
    
            // If not, increment the quantity and add the new barcode number
            $existingCart->quantity += 1; // Increment by 1
            $barcodeNumbers[] = $barcodeNumber; // Add the new barcode number
            $existingCart->barcode_numbers = json_encode($barcodeNumbers); // Update the barcode numbers
            
            $existingCart->save(); // Save the updated cart
        } else {
            // If no existing cart for this product, create a new PosReturnCart entry
            $posCart = new PosReturnCart();
            $posCart->product_sku = $productDetails->SKU;
            $posCart->quantity = 1; // Start with a quantity of 1
            $posCart->date_added = now();
            $posCart->price = $productDetails->SRP;
            $posCart->consign = $productDetails->Consign;
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
    
        // Loop through the barcode_numbers and do not modify `is_used`
        $barcodeNumbers = json_decode($posCartItem->barcode_numbers, true); // Decode the barcode_numbers array
        foreach ($barcodeNumbers as $barcodeNumber) {
            $barcode = ProductBarcode::where('barcode_number', $barcodeNumber)->first();
            
            if ($barcode) {
                // No action needed for `is_used` here
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
            // Check if the quantity to transfer is greater than the available stock in the Product model
            $product = Product::where('SKU', $cart->product_sku)->first();
            
            if ($product) {
                if ($cart->quantity > $product->Stock) {
                    // Not enough stock to transfer
                    return redirect()->route('pos.index')->with('error', 'Not enough stock available to transfer this item.');
                }
            } else {
                // If no product is found, handle the case (you could throw an exception or log an error)
                return redirect()->route('pos.index')->with('error', "Product with SKU {$cart->product_sku} not found.");
            }
    
            // First, update the Product model's stock (deduct from the Product model)
            $product->Stock -= $cart->quantity;
            $product->save();
    
            // Find or create the inventory item based on SKU
            $storeInventory = StoreInventory::where('SKU', $cart->product_sku)
                                            ->where('store_id', 7)
                                            ->first();
    
            // Decode the barcode numbers from PosReturnCart to ensure it is an array
            $barcodeNumbers = is_string($cart->barcode_numbers) ? json_decode($cart->barcode_numbers, true) : $cart->barcode_numbers;
    
            if ($storeInventory) {
                // Update the stock for existing inventory after deducting from the product stock
                $storeInventory->Stocks += $cart->quantity;
    
                // Decode existing barcode numbers and ensure it's an array (handle null case)
                $existingBarcodeNumbers = json_decode($storeInventory->barcode_numbers, true) ?? [];
    
                // Merge the new barcode numbers with the existing ones
                $mergedBarcodeNumbers = array_merge($existingBarcodeNumbers, $barcodeNumbers);
    
                // Update the barcode numbers in the inventory
                $storeInventory->barcode_numbers = json_encode($mergedBarcodeNumbers); // Save as a JSON string
                $storeInventory->save();
    
            } else {
                // Create a new inventory entry if it doesn't exist
                StoreInventory::create([
                    'SKU' => $cart->product_sku,
                    'ProductID' => $cart->product_bundle_id,
                    'Stocks' => $cart->quantity,
                    'Consign' => $cart->consign,
                    'SPR' => $cart->price,
                    'store_id' => 7, // Set this based on your store logic
                    'barcode_numbers' => json_encode($barcodeNumbers), // Save barcode numbers as JSON
                ]);
            }
    
            // Now update the ProductBarcode model for each barcode number
            foreach ($barcodeNumbers as $barcodeNumber) {
                // Find the barcode entry
                $productBarcode = ProductBarcode::where('barcode_number', $barcodeNumber)->first();
    
                if ($productBarcode) {
                    // Update the barcode location
                    $productBarcode->barcode_location = $storeInventory->store_id . ' - ' . $storeInventory->store->store_name;
                    $productBarcode->save(); // Save the updated record
                }
            }
    
            // Delete the item from the PosReturnCart after transfer
            $cart->delete();
        }
    
        return redirect()->route('pos.index')->with('success', 'Transfer completed successfully.');
    }                  
}
