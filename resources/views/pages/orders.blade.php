@extends('layout')

@section('title', 'Orders')

@php
    use Carbon\Carbon;
@endphp


@section('content')
    <div class="orderpage-loading-overlay" id="orderPageloadingOverlay">
        <div class="orderpage-spinner"></div>
    </div>

    <div>
        <h1 class="text-center mb-4">Orders</h1>

        <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
            <div class="form-row align-items-end">
                <div class="col-md-4">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success mt-4">Filter</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-success mt-4 ml-2">Clear</a>
                </div>
            </div>
        </form>

        <div>
            @forelse ($orders as $order)
                <div>
                    <div class="card order-card shadow-lg rounded">
                        <div class="card-body">
                            <h5 class="card-title">Order ID: {{ $order->id }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Customer: {{ $order->first_name }} {{ $order->last_name }}</h6>
                            <h6 class="card-subtitle mb-2 text-muted">Store Name: {{ $order->store_name }}</h6>
                            <p><strong>Email:</strong> {{ $order->email }}</p>
                            <p><strong>Address:</strong> {{ $order->address }}</p>

                            <h6 class="mt-3"><strong>Products Ordered:</strong></h6>
                            <table class="table table-sm mt-2">
                                <thead>
                                    <tr>
                                        <th class="d-none">Cart ID</th>
                                        <th>Bundle Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Action</th> <!-- Add an Action column for Edit and Save -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $products = json_decode($order->products_ordered, true);
                                    @endphp
                                    @foreach ($products as $product)
                                        <tr id="product-row-{{ $product['cart_id'] }}">
                                            <!-- Pass order_id and cart_id -->
                                            <td class="d-none">{{ $product['cart_id'] }}</td>
                                            <td>{{ $product['bundle_name'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>
                                                <span id="quantity-display-{{ $order->id }}-{{ $product['cart_id'] }}">{{ $product['quantity'] }}</span>
                                                <input type="number" id="quantity-input-{{ $order->id }}-{{ $product['cart_id'] }}" value="{{ $product['quantity'] }}" class="form-control" style="display: none;">
                                            </td>                                            
                                            <td>₱{{ number_format($product['price'], 2) }}</td>
                                            <td>
                                                <div class="actions-products-ordered">
                                                    <button class="btn btn-sm btn-primary" onclick="editQuantity({{ $loop->index }}, {{ $order->id }}, '{{ $product['cart_id'] }}')" id="edit-button-{{ $order->id }}-{{ $product['cart_id'] }}">Edit</button>
                                                    <button class="btn btn-sm btn-success" onclick="saveQuantity({{ $order->id }}, '{{ $product['cart_id'] }}')" id="save-button-{{ $order->id }}-{{ $product['cart_id'] }}" style="display: none;">Save</button>
                                                    <button class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $loop->index }}, {{ $order->id }}, '{{ $product['cart_id'] }}')" id="cancel-button-{{ $order->id }}-{{ $product['cart_id'] }}" style="display: none;">Cancel</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>                                                                                                             
                            <p class="mt-3"><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
                            <p><strong>Order Date:</strong> {{ Carbon::parse($order->createdAt)->format('F j, Y, g:i A') }}</p>
                            <p><strong>User Estimated Items Sold Per Month:</strong> {{ $order->user ? $order->user->estimated_items_sold_per_month : 'N/A' }}</p>
                            
                            <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="order-status"><strong>Order Status:</strong></label>
                                    <select name="order_status" class="form-control" onchange="showLoading(); this.form.submit();">
        
                                        <!-- If the order status is "Processing", show "Processing", "Packed", "Canceled" -->
                                        @if ($order->order_status == 'Processing')
                                            <option value="Processing" selected>Processing</option>
                                            <option value="Packed" {{ $order->order_status == 'Packed' ? 'selected' : '' }}>Packed</option>
                                            <option value="Canceled" {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                        @endif
                                
                                        <!-- If the order status is "Packed", show "Processing", "Packed", "Shipped", "Canceled" -->
                                        @if ($order->order_status == 'Packed')
                                            <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="Packed" selected>Packed</option>
                                            <option value="Shipped" {{ $order->order_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="Canceled" {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                        @endif
                                
                                        <!-- If the order status is "Shipped", show "Processing", "Packed", "Shipped", "Delivered", "Canceled" -->
                                        @if ($order->order_status == 'Shipped')
                                            <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="Packed" {{ $order->order_status == 'Packed' ? 'selected' : '' }}>Packed</option>
                                            <option value="Shipped" selected>Shipped</option>
                                            {{--<option value="Delivered" {{ $order->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>--}}
                                            <option value="Canceled" {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                        @endif
                                
                                        <!-- If the order status is "Delivered", show only "Delivered" -->
                                        @if ($order->order_status == 'Delivered')
                                            <option value="Delivered" selected>Delivered</option>
                                        @endif
                                
                                        <!-- If the order status is "Canceled", show only "Canceled" -->
                                        @if ($order->order_status == 'Canceled')
                                            <option value="Canceled" selected>Canceled</option>
                                        @endif
                                
                                    </select>
                                    
                                </div>
                            </form>
                            
                            @if ($order->order_status === 'Shipped') 
                                <form method="POST" action="{{ route('orders.uploadProofOfReceive', $order->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="proof_of_receive"><strong>Upload Proof of Receive (Image):</strong></label>
                                        <input type="file" name="proof_of_receive" accept="image/*" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success">Upload Proof</button>
                                </form>
                            @endif

                            @if ($order->order_status === 'Delivered')  
                                <!-- Display the proof of receive image if the order is delivered -->
                                <div class="mt-4">
                                    <h6><strong>Proof of Receive:</strong></h6>
                                    {{--@if ($order->proof_of_receive)
                                        <img src="data:image/jpeg;base64,{{ $order->proof_of_receive }}" alt="Proof of Receive" class="proof-image">
                                    @endif--}}
                                    {{--<img src="data:image/jpeg;base64,{{ $order->proof_of_receive }}" alt="Proof of Receive" class="proof-image">--}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-warning">No orders found.</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="{{ asset('js/orders.js?v=2.5') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/ordersPage.css?v=2.5') }}">
@endsection
