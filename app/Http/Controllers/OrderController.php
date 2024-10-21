<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
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
        $request->validate([
            'order_status' => 'required|string|in:Processing,Packed,Shipped,Delivered,Canceled,Approved,Declined',
        ]);
        
        $order = Order::findOrFail($id);
        $order->order_status = $request->order_status;
        $order->save();
    
        // Handle stock updates and other logic based on order status
        if ($order->order_status === 'Shipped') {
            $productsOrdered = json_decode($order->products_ordered, true);
            
            foreach ($productsOrdered as $product) {
                $productSku = $product['product_sku'];
                $quantityOrdered = $product['quantity'];
    
                // Find the product by SKU
                $productModel = Product::where('sku', $productSku)->first();
                if ($productModel) {
                    // Calculate new stock
                    $newStock = $productModel->stock - $quantityOrdered;
    
                    // Update stock in the database
                    $productModel->stock = $newStock;
                    $productModel->save();
                }
            }
        }
    
        // Send email to the customer
        Mail::to($order->email)->send(new AdminOrderStatus($order));
    
        // Notify all admin users about the status update
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new AdminNotificationsForOrderStatus($order));
        }
    
        return redirect()->route('orders.index')->with('success', 'Order status updated successfully!');
    }    
}
