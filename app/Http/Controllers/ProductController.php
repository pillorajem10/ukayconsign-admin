<?php

namespace App\Http\Controllers;
use App\Models\Product; // Import the Product model
use App\Models\Batch; // Import the Product model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all(); // Fetch all products from the database
        return view('pages.home', compact('products')); // Pass data to the view
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.addProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'SKU' => 'required|string|max:50|unique:products,SKU',
            'Bundle' => 'nullable|string|max:255',
            'Type' => 'nullable|string|max:50',
            'Style' => 'nullable|string|max:50',
            'Color' => 'nullable|string|max:50',
            'Gender' => 'nullable|string|max:50',
            'Category' => 'nullable|string|max:50',
            'Bundle_Qty' => 'nullable|integer',
            'Consign' => 'nullable|numeric',
            'Cash' => 'nullable|numeric',
            'SRP' => 'nullable|string|max:50',
            'maxSRP' => 'nullable|string|max:50',
            'PotentialProfit' => 'nullable|numeric',
            'Date' => 'nullable|date',
            'Cost' => 'nullable|numeric',
            'Stock' => 'nullable|integer',
            'Supplier' => 'nullable|string|max:255',
            'Image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'Secondary_Img' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'Img_color' => 'nullable|string|max:255',
            'is_hidden' => 'nullable|boolean',
            'Batch_number' => 'nullable|string|max:255',
            'Bale' => 'nullable|string|max:255',
            'createdAt' => 'nullable|date',
            'batches' => 'nullable|text',
        ], [
            'SKU.unique' => "{$request->SKU} is already existed in products." // Custom error message
        ]);
    
        // Create the product
        $product = new Product($validatedData);
    
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
            'maxSRP' => $product->maxSRP,
            'PotentialProfit' => $product->PotentialProfit,
            'Cost' => $product->Cost,
            'Stock' => $product->Stock,
            'Supplier' => $product->Supplier,
            'Img_color' => $product->Img_color,
            'Date' => now(), // Use current date
            'Bale' => $bale,
            'Batch_number' => $batchNumber,
        ]);
    
        // Update the product to include the batch number
        $product->batches = json_encode([['Batch_number' => $batchNumber]]);
        $product->Batch_number = $batchNumber; // Add this line
        $product->save();
    
        return redirect('/')->with('success', 'Product added successfully!');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
