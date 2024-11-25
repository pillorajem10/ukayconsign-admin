<?php

namespace App\Http\Controllers;
use App\Models\Product; // Import the Product model
use App\Models\ProductBarcode; // Import the Product model
use App\Models\Batch; // Import the Product model
use App\Models\ReceivedProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG; // For PNG output


class ProductController extends Controller
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
        // Get the search input from the request
        $search = $request->input('search');
        $page = $request->input('page', 1); // Default to page 1 if not provided
        
        // Clear the search session if the search is empty
        if (empty($search)) {
            session()->forget('search');
        } else {
            // Store search term and current page in session if search is present
            session(['search' => $search]);
        }
    
        // Store current page in session
        if ($page) {
            session(['page' => $page]);
        }
    
        // Fetch products with pagination, apply search filter if provided
        $products = Product::when($search, function ($query) use ($search) {
            return $query->where('ProductID', 'like', '%' . $search . '%');
        })->paginate(10);
    
        // Return the view with products, search term, and current page
        return view('pages.home', compact('products', 'search', 'page'));
    }    
    
      

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all(); // Fetch all suppliers
        return view('pages.addProduct', compact('suppliers')); // Pass suppliers to the view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'SKU' => 'required|string|max:50|unique:usc_products,SKU',
            'Bundle' => 'nullable|string|max:255',
            'Type' => 'nullable|string|max:50',
            'Style' => 'nullable|string|max:50',
            'Color' => 'nullable|string|max:50',
            'Gender' => 'nullable|string|max:50',
            'Category' => 'nullable|string|max:50',
            'Bundle_Qty' => 'nullable|integer',
            'Consign' => 'nullable|numeric',
            'SRP' => 'nullable|string|max:50',
            'PotentialProfit' => 'nullable|numeric',
            'Cost' => 'nullable|numeric',
            'Stock' => 'nullable|integer',
            'Supplier' => 'nullable|string|max:255',
            'Image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'Secondary_Img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'details_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'Img_color' => 'nullable|string|max:255',
            'is_hidden' => 'nullable|boolean',
            'Batch_number' => 'nullable|string|max:255',
            'Bale' => 'nullable|string|max:255',
            'createdAt' => 'nullable|date',
        ], [
            'SKU.unique' => "{$request->SKU} is already existed in products." // Custom error message
        ]);
    
        // Create the product
        $product = new Product($validatedData);
    
        // Handle image uploads
        if ($request->hasFile('Image')) {
            $product->Image = file_get_contents($request->file('Image')->getRealPath());
        }
        if ($request->hasFile('Secondary_Img')) {
            $product->Secondary_Img = file_get_contents($request->file('Secondary_Img')->getRealPath());
        }
    
        // Handle details images
        if ($request->hasFile('details_images')) {
            $detailsImages = [];
            foreach ($request->file('details_images') as $image) {
                // Read the file and encode it to Base64
                $detailsImages[] = base64_encode(file_get_contents($image->getRealPath()));
            }
            $product->details_images = json_encode($detailsImages); // Store as JSON
        }
    
        // Generate ProductID from concatenation
        $product->ProductID = trim("{$request->Type} {$request->Style} {$request->Color} {$request->Gender} {$request->Category}");
    
        // Save the product first to get the SKU for batch
        $product->save();
    
        // Generate batch number
        $date = now()->format('mdy'); // Format: MMDDYY
        $bale = $request->Bale ?? 'Unknown'; // Get Bale from request or default to 'Unknown'
    
        // Adjust batch base to include SKU
        $batchBase = "{$date}-{$product->SKU}-{$bale}";
    
        // Count existing batches with exact SKU and date
        $existingBatchCount = Batch::where('Batch_number', 'like', "{$date}-{$product->SKU}-{$bale}-%")->count();
    
        $batchSuffix = str_pad($existingBatchCount + 1, 2, '0', STR_PAD_LEFT); // Pad with zeros
        $batchNumber = "{$batchBase}-{$batchSuffix}";
    
        // Create the batch entry
        /*
        Batch::create([
            'SKU' => $product->SKU,
            'Bundle' => $product->Bundle,
            'ProductID' => $product->ProductID,
            'Type' => $product->Type,
            'Style' => $product->Style,
            'Color' => $product->Color,
            'Gender' => $product->Gender,
            'Category' => $product->Category,
            'Bundle_Qty' => $product->Bundle_Qty,
            'Consign' => $product->Consign,
            'SRP' => $product->SRP,
            'PotentialProfit' => $product->PotentialProfit,
            'Cost' => $product->Cost,
            'Stock' => $product->Stock,
            'Supplier' => $product->Supplier,
            'Bale' => $bale,
            'Batch_number' => $batchNumber,
        ]);
        */
    
        // Update the product to include the batch number
        $product->Batch_number = $batchNumber; // Add this line
        $product->save();
    
        // Optionally, handle barcode generation here
        /*
        for ($i = 0; $i < $product->Stock; $i++) {
            $barcodeNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Generate a random 6-digit number
    
            // Create barcode image using Picqer
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $barcodeGenerator->getBarcode($barcodeNumber, $barcodeGenerator::TYPE_CODE_128);
    
            // Create and save the barcode entry
            ProductBarcode::create([
                'product_sku' => $product->SKU,
                'barcode_number' => $barcodeNumber,
                'is_used' => 0, // Default to not used
                'received_product_id' => null, // Default to null
                'batch_number' => $batchNumber,
                'barcode_image' => $barcodeImage, // Save the barcode image
            ]);
        }
        */
    
        return redirect()->route('products.index', [
            'search' => session('search'), 
            'page' => session('page', 1)  // Default to page 1 if not found in the session
        ])->with('success', 'Product added successfully!');
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
        // Find the product by SKU
        $product = Product::findOrFail($id);
        
        // Pass the product data to the view
        return view('pages.editProduct', compact('product'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'Bundle' => 'nullable|string|max:255',
            'Type' => 'nullable|string|max:50',
            'Style' => 'nullable|string|max:50',
            'Color' => 'nullable|string|max:50',
            'Gender' => 'nullable|string|max:50',
            'Category' => 'nullable|string|max:50',
            'Bundle_Qty' => 'nullable|integer',
            'Consign' => 'nullable|numeric',
            'SRP' => 'nullable|string|max:50',
            'PotentialProfit' => 'nullable|numeric',
            'Image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'Secondary_Img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'details_images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Find the product by SKU
        $product = Product::findOrFail($id);
        
        // Store the original SRP before updating
        $originalSRP = $product->SRP;
    
        // Update product fields
        $product->fill($validatedData);
    
        // Handle image uploads (same logic as before)
        if ($request->hasFile('Image')) {
            $product->Image = file_get_contents($request->file('Image')->getRealPath());
        }
        if ($request->hasFile('Secondary_Img')) {
            $product->Secondary_Img = file_get_contents($request->file('Secondary_Img')->getRealPath());
        }
    
        // Handle multiple details images
        if ($request->hasFile('details_images')) {
            $detailsImages = [];
            foreach ($request->file('details_images') as $image) {
                // Read the file and encode it to Base64
                $detailsImages[] = base64_encode(file_get_contents($image->getRealPath()));
            }
            $product->details_images = json_encode($detailsImages); // Store as JSON
        }
    
        // Save the updated product
        $product->save();
    
        // If the SRP has changed, update the corresponding ProductBarcode records
        if ($product->SRP !== $originalSRP) {
            $productBarcodes = ProductBarcode::where('product_sku', $id)->get();
            
            foreach ($productBarcodes as $barcode) {
                $barcode->product_retail_price = $product->SRP; // Update retail price to the new SRP
                $barcode->save(); // Save the updated barcode
            }
        }
    
        // After updating the product, we redirect back to the product list
        // and append the search term and page from the session to the URL
        return redirect()->route('products.index', [
            'search' => session('search'), 
            'page' => session('page', 1)  // Default to page 1 if not found in the session
        ])->with('success', 'Product updated successfully!');
    }
    
    
    
        
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete all barcodes associated with this product's SKU
        $product->productBarcodes()->delete();
    
        // Delete all batches associated with this product's SKU
        $product->batches()->delete();
    
        // Delete all received products associated with this product's SKU
        $product->receivedProducts()->delete();
    
        // Delete the product
        $product->delete();
    
        return redirect()->route('products.index', [
            'search' => session('search'), 
            'page' => session('page', 1)  // Default to page 1 if not found in the session
        ])->with('success', 'Product deleted successfully!');
    }  
    
    public function showInventoryPage(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');
    
        // Split the search string into words
        $searchWords = $search ? explode(' ', $search) : [];
    
        // Fetch the ProductID and Stock for all products, apply the search if provided, and paginate with 10 items per page
        $products = Product::select('ProductID', 'Stock')
            ->when($searchWords, function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    // Add a condition to check if each word exists in the ProductID
                    $query->where('ProductID', 'like', '%' . $word . '%');
                }
            })
            ->paginate(10);
    
        // Pass the paginated products and search term to the view
        return view('pages.inventoryPage', compact('products', 'search'));
    }       
}
