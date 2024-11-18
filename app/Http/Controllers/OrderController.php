<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\Auth;

use App\Mail\AdminOrderStatus;
use App\Mail\AdminNotificationsForOrderStatus;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
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
        $query = Order::with('user'); // Eager load the User model
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            // Ensure to include the end of the day for the end date
            $query->whereBetween('createdAt', [
                $request->input('start_date') . ' 00:00:00',
                $request->input('end_date') . ' 23:59:59',
            ]);
        }
        
        $orders = $query->orderBy('createdAt', 'desc')->get(); // Fetch all orders in descending order
        return view('pages.orders', compact('orders')); // Pass the orders to the view
    }    

    public function updateStatus(Request $request, $id)
    {
        // Validate the request input for order status
        $request->validate([
            'order_status' => 'required|string|in:Processing,Packed,Shipped,Delivered,Canceled,Approved,Declined',
        ]);
        
        // Find the order by ID
        $order = Order::findOrFail($id);
        
        // Update the order status
        $order->order_status = $request->order_status;
        $order->save();
        
        // Handle logic when the order status is 'Shipped'
        if ($order->order_status === 'Shipped') {
            $productsOrdered = json_decode($order->products_ordered, true);
            
            // Iterate over the products ordered
            foreach ($productsOrdered as $product) {
                $productSku = $product['product_sku'];
                $quantityOrdered = $product['quantity'];
    
                // Find the product by SKU
                $productModel = Product::where('sku', $productSku)->first();
                if ($productModel) {
                    // Calculate new Stock
                    $newStock = $productModel->Stock - $quantityOrdered;
        
                    // Update Stock in the database
                    $productModel->Stock = $newStock;
                    $productModel->save();
                }
            }
        }
    
        // Handle inventory update when the order status is 'Delivered'
        if ($order->order_status === 'Delivered') {
            // Decode the JSON string into an array of products ordered
            $productsOrdered = json_decode($order->products_ordered, true);
            
            // Check if decoding was successful and if it's an array
            if (is_array($productsOrdered)) {
                foreach ($productsOrdered as $product) {
                    // Check if SKU already exists in StoreInventory for the specific store
                    $inventoryItem = StoreInventory::where('SKU', $product['product_sku'])
                        ->where('store_id', $product['store_id']) // Ensure we match store ID
                        ->first();
                    
                    if ($inventoryItem) {
                        // If it exists, update the Stocks
                        $inventoryItem->Stocks += $product['quantity'];
                        $inventoryItem->save();
                    } else {
                        // If it doesn't exist, create a new record in StoreInventory
                        StoreInventory::create([
                            'SKU' => $product['product_sku'],
                            'ProductID' => $product['product_id'],
                            'Stocks' => $product['quantity'],
                            'Consign' => $product['product_consign'],
                            'SPR' => $product['product_srp'],
                            'store_id' => $product['store_id'],
                        ]);
                    }
                }
            } else {
                // Handle error if products_ordered is not valid JSON or not an array
                return redirect()->route('orders.index')->with('error', 'Invalid product data format!');
            }
        }
    
        // Notify all admin users about the status update (email functionality can be added later)
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            // Send notifications (emails can be configured)
            // Mail::to($admin->email)->send(new AdminNotificationsForOrderStatus($order));
        }
    
        // Redirect back with success message
        return redirect()->route('orders.index')->with('success', 'Order status updated successfully!');
    }

    public function uploadProofOfReceive(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'proof_of_receive' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Limit size and allow image types
        ]);

        // Find the order by ID
        $order = Order::findOrFail($id);

        // Get the uploaded image file
        $image = $request->file('proof_of_receive');

        // Read the image as binary data and convert to base64
        $imageData = file_get_contents($image->getRealPath());
        $base64Image = base64_encode($imageData); // Convert image to base64

        // Save the base64 image to the database in the proof_of_receive column
        $order->proof_of_receive = $base64Image;

        // Update the order status to "Delivered"
        $order->order_status = 'Delivered';
        
        // Decode the products_ordered JSON and update the inventory
        $productsOrdered = json_decode($order->products_ordered, true);
        
        // Check if decoding was successful and if it's an array
        if (is_array($productsOrdered)) {
            foreach ($productsOrdered as $product) {
                // Check if SKU already exists in StoreInventory for the specific store
                $inventoryItem = StoreInventory::where('SKU', $product['product_sku'])
                    ->where('store_id', $product['store_id']) // Ensure we match store ID
                    ->first();
                
                if ($inventoryItem) {
                    // If it exists, update the Stocks
                    $inventoryItem->Stocks += $product['quantity'];
                    $inventoryItem->save();
                } else {
                    // If it doesn't exist, create a new record in StoreInventory
                    StoreInventory::create([
                        'SKU' => $product['product_sku'],
                        'ProductID' => $product['product_id'],
                        'Stocks' => $product['quantity'],
                        'Consign' => $product['product_consign'],
                        'SPR' => $product['product_srp'],
                        'store_id' => $product['store_id'],
                    ]);
                }
            }
        } else {
            // Handle error if products_ordered is not valid JSON or not an array
            return redirect()->route('orders.index')->with('error', 'Invalid product data format!');
        }

        // Save the updated order with proof of receipt and order status
        $order->save();

        // Redirect back with success message
        return redirect()->route('orders.index')->with('success', 'Proof of receipt uploaded successfully and order status updated to Delivered.');
    }
      
    
    public function updateQuantity(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'updated_product' => 'required|string',
        ]);
    
        // Decode the updated product data from the request
        $updatedProductData = json_decode($request->input('updated_product'), true);
    
        // Find the order by its ID
        $order = Order::findOrFail($updatedProductData['order_id']);
    
        // Decode the products_ordered field (JSON)
        $productsOrdered = json_decode($order->products_ordered, true);
    
        // Initialize a variable to store the total price
        $newTotalPrice = 0;
    
        // Loop through the products to find the one to update and recalculate the total price
        foreach ($productsOrdered as $index => $product) {
            // If the cart_id matches the updated product, update the quantity
            if ($product['cart_id'] == $updatedProductData['cart_id']) {
                // Update the quantity of the product
                $productsOrdered[$index]['quantity'] = $updatedProductData['quantity'];
            }
    
            // Calculate the subtotal for the current product (quantity * price)
            $subtotal = $productsOrdered[$index]['quantity'] * $productsOrdered[$index]['price'];
    
            // Add the product's subtotal to the new total price
            $newTotalPrice += $subtotal;
        }
    
        // Save the updated products_ordered JSON back to the order
        $order->products_ordered = json_encode($productsOrdered);
        $order->total_price = $newTotalPrice;  // Update the total price
        $order->save();
    
        // Redirect back to the orders list page with a success message
        return redirect()->route('orders.index')->with('success', 'Order Quantity Updated Successfully!');
    }
         
}
