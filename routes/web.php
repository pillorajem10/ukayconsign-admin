<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceivedProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreInventoryController;
use App\Http\Controllers\ProductBarcodesController;

use Illuminate\Support\Facades\Auth;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// PRODUCT ROUTES
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/delete/{product:SKU}', [ProductController::class, 'destroy'])->name('products.destroy');
// Add this route for invalid GET requests
Route::get('/products/delete/{product}', function() {
    return redirect()->route('products.index')->with('error', 'Invalid request method. Please use the delete action.');
});
Route::get('/products/update/{product}', function() {
    return redirect()->route('products.index')->with('error', 'Invalid request method. Please use the update action.');
});

// ORDERS (transactions) ROUTES
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');


// RECEIVED PRODUCTS
Route::get('/received-products', [ReceivedProductController::class, 'index'])->name('receivedProducts.index');


// AUTH ROUTES
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirect to the login page
})->name('logout'); // Naming the route


// SUPPLIERS ROUTES
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::delete('/suppliers/delete/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
Route::patch('/suppliers/update/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');

// Handle invalid requests to the delete route
Route::get('/suppliers/delete/{supplier}', function() {
    return redirect()->route('suppliers.index')->with('error', 'Invalid request method. Please use the delete action.');
});
Route::get('/suppliers/update/{supplier}', function() {
    return redirect()->route('suppliers.index')->with('error', 'Invalid request method. Please use the update action.');
});


// SUPPLIERS CREATE ROUTES
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');

// STORE ROUTES
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');

// STORE INVENTORY ROUTES
Route::get('/store-inventory', [StoreInventoryController::class, 'index'])->name('store-inventory.index');

// PRODUCT BARCODES ROUTE
Route::get('/product-barcodes', [ProductBarcodesController::class, 'index'])->name('product-barcodes.index');

// DASHBOARD
Route::get('/dashboard', function () {
    return view('pages.dashboard'); // Points to your dashboard view
})->middleware('auth'); // Ensures the user is authenticated
