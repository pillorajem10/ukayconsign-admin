@extends('layout')

@section('title', 'Orders')

@section('content')
    <div>
        <h1 class="text-center">Orders</h1>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Products Ordered</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->first_name }}</td>
                        <td>{{ $order->last_name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->address }}</td>
                        <td>
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
                                            <td>${{ number_format($product['price'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->order_date }}</td>
                        <td>
                            <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="order_status" onchange="this.form.submit()">
                                    <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Packed" {{ $order->order_status == 'Packed' ? 'selected' : '' }}>Packed</option>
                                    <option value="Shipped" {{ $order->order_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="Delivered" {{ $order->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Canceled" {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
