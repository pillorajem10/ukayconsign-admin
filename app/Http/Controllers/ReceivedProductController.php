<?php

namespace App\Http\Controllers;

use App\Models\ReceivedProduct;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\StoreInventory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ReceivedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Require authentication for all methods in this controller
    }
    
    public function index(Request $request)
    {
        $currentPage = $request->get('page', 1);
        session(['received_products_page' => $currentPage]); 

        $receivedProducts = ReceivedProduct::orderBy('createdAt', 'desc')->paginate(10);
        return view('pages.receivedProducts', compact('receivedProducts'));
    }       

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productSku = $request->query('product_sku'); // Get SKU from query
        $suppliers = Supplier::all(); // Fetch all suppliers
    
        return view('pages.addReceiveProduct', compact('productSku', 'suppliers')); // Pass SKU and suppliers to the view
    }
    
    

    /**
     * Store a newly created resource in storage.
     */

    /*
        public function store(Request $request)
        {
            // Validate the request data
            $request->validate([
                'supplier' => 'required|string|max:255',
                'product_sku' => 'required|string|max:255',
                'bale' => 'required|string|max:255',
                'quantity_received' => 'required|integer',
                'cost' => 'required|numeric',
            ]);
        
            // Find the product using the SKU
            $product = Product::where('SKU', $request->product_sku)->first();
        
            // Check if the product exists
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found.');
            }
        
            // Update the product stock
            $product->Stock += $request->quantity_received;
            $product->save();
        
            // Create a new received product
            ReceivedProduct::create([
                'supplier' => $request->supplier,
                'product_sku' => $request->product_sku,
                'quantity_received' => $request->quantity_received,
                'printed_barcodes' => $request->printed_barcodes,
                'bale' => $request->bale,
                'is_voided' => $request->is_voided,
                'batch_number' => $request->batch_number,
                'cost' => $request->cost,
                'createdAt' => now()->setTimezone('Asia/Manila'), // Set to Philippine timezone
            ]);
        
            return redirect()->route('receivedProducts.index')->with('success', 'Product received successfully!');
        }
    */

    
    
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'supplier' => 'required|string|max:255',
            'product_sku' => 'required|string|max:255',
            'bale' => 'required|string|max:255',
            'quantity_received' => 'required|integer',
            'cost' => 'required|numeric',
        ]);

        $product = Product::where('SKU', $request->product_sku)->first();
        
        // Check if the product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
    
        /*
            $product = Product::where('SKU', $request->product_sku)->first();
        
            // Check if the product exists
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found.');
            }
        */
    
        // Update the product stock
        $product->Stock += $request->quantity_received;
        $product->save();
        
    
        // Create a new received product
        ReceivedProduct::create([
            'supplier' => $request->supplier,
            'product_sku' => $request->product_sku,
            'quantity_received' => $request->quantity_received,
            'printed_barcodes' => $request->printed_barcodes,
            'bale' => $request->bale,
            'is_voided' => $request->is_voided,
            'batch_number' => $request->batch_number,
            'cost' => $request->cost,
            'createdAt' => now()->setTimezone('Asia/Manila'), // Set to Philippine timezone
        ]);
    
        // Update StoreInventory for store_id = 7

        /*
        $storeInventory = StoreInventory::where('SKU', $request->product_sku)
            ->where('store_id', 7)
            ->first();
    
        // If the store inventory entry exists, update the stocks
        if ($storeInventory) {
            $storeInventory->Stocks += $request->quantity_received;
            $storeInventory->save();
        } else {
            // Create a new StoreInventory entry with ProductID, Consign, and SPR from Product
            StoreInventory::create([
                'SKU' => $request->product_sku,
                'ProductID' => $product->ProductID, // Assuming ProductID is a field in the Product model
                'Stocks' => $request->quantity_received,
                'Consign' => $product->Consign, // Use the Consign from the Product model
                'SPR' => $product->SRP, // Use the SRP from the Product model
                'store_id' => 7,
            ]);
        */
        
    
        return redirect()->route('receivedProducts.index')->with('success', 'Product received successfully!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function generateBarcodes($id)
    {
        // Find the received product by ID
        $receivedProduct = ReceivedProduct::findOrFail($id);
    
        // Check if barcodes are already printed
        if ($receivedProduct->printed_barcodes) {
            return redirect()->route('receivedProducts.index')->with('error', 'Barcodes have already been printed for this product.');
        }
    
        // Update the printed_barcodes status
        $receivedProduct->printed_barcodes = true;
        $receivedProduct->save();
    
        // Find the corresponding product using SKU
        $product = Product::where('SKU', $receivedProduct->product_sku)->first();
    
        if (!$product) {
            return redirect()->route('receivedProducts.index')->with('error', 'Product not found.'); // Handle product not found
        }
    
        // Generate barcodes
        for ($i = 0; $i < $receivedProduct->quantity_received; $i++) {
            // Generate a random barcode number
            $barcodeNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Random 6-digit number
    
            // Check if barcode already exists in the database
            if (ProductBarcode::where('barcode_number', $barcodeNumber)->exists()) {
                // If the barcode exists, return the error message and stop further processing
                return redirect()->route('receivedProducts.index')->with('error', 'A barcode with this number already exists. Void the request and generate barcodes for this receive again');
            }
    
            // Create barcode image using Picqer
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $barcodeGenerator->getBarcode($barcodeNumber, $barcodeGenerator::TYPE_CODE_128); // Get binary image data
    
            // Save the barcode entry
            ProductBarcode::create([
                'product_sku' => $product->SKU,
                'barcode_number' => $barcodeNumber,
                'is_used' => 0, // Default to not used
                'received_product_id' => $receivedProduct->id, // Link to the received product
                'batch_number' => $receivedProduct->batch_number,
                'barcode_image' => $barcodeImage, // Save the binary image data directly
                'product_retail_price' => $product->SRP, // Added: product retail price (SRP from Product model)
                'bale_received' => $receivedProduct->bale, // Added: bale received from ReceivedProduct model
                'supplier' => $receivedProduct->supplier, // Added: supplier from ReceivedProduct model
                'barcode_location' => 'USC', // Added: default barcode location
            ]);
        }
    
        return redirect()->route('receivedProducts.index')->with('success', 'Barcodes generated successfully!');
    }
    
    
      

    /**
     * Remove the specified resource from storage.
     */
    public function void($id)
    {
        // Find the received product by ID
        $receivedProduct = ReceivedProduct::findOrFail($id);
    
        // Check if the product is already voided
        if ($receivedProduct->is_voided) {
            return redirect()->route('receivedProducts.index')->with('error', 'Product is already voided.');
        }
    
        // Find the corresponding StoreInventory for store_id = 7
        
        $storeInventory = StoreInventory::where('SKU', $receivedProduct->product_sku)
            ->where('store_id', 7)
            ->first();
        

        
        $product = Product::where('SKU', $receivedProduct->product_sku)->first();

        // Deduct the received quantity from the product stock
        if ($product) {
            $product->Stock -= $receivedProduct->quantity_received;
            $product->save(); // Save the updated product
        }
        
    
        // If the store inventory exists, decrease the Stocks
    
        /*
            if ($storeInventory) {
                $storeInventory->Stocks -= $receivedProduct->quantity_received;
        
                // Ensure stocks do not go negative
                if ($storeInventory->Stocks < 0) {
                    $storeInventory->Stocks = 0;
                }
        
                $storeInventory->save(); // Save the updated stock
            }
        */
    
    
        // Delete associated barcodes
        ProductBarcode::where('received_product_id', $receivedProduct->id)->delete();
    
        // Mark the received product as voided
        $receivedProduct->is_voided = true;
        $receivedProduct->save(); // Save the updated received product
    
        return redirect()->route('receivedProducts.index')->with('success', 'Product voided, stock updated, and barcodes deleted successfully.');
    }
       
}
