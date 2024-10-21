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
                                        <th>Cart ID</th>
                                        <th>Bundle Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $products = json_decode($order->products_ordered, true);
                                    @endphp
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product['cart_id'] }}</td>
                                            <td>{{ $product['bundle_name'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>{{ $product['quantity'] }}</td>
                                            <td>₱{{ number_format($product['price'], 2) }}</td>
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
                                        <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Packed" {{ $order->order_status == 'Packed' ? 'selected' : '' }}>Packed</option>
                                        <option value="Shipped" {{ $order->order_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Canceled" {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                        <option value="Approved" {{ $order->order_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Declined" {{ $order->order_status == 'Declined' ? 'selected' : '' }}>Declined</option>
                                    </select>
                                </div>
                            </form>                            
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

    <script src="{{ asset('js/orders.js?v=1.7') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/ordersPage.css?v=1.7') }}">
@endsection
