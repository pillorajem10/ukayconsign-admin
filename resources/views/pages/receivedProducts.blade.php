@extends('layout')

@section('title', 'Received Products')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Received Products</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Product SKU</th>
                    <th>Quantity Received</th>
                    <th>Printed Barcodes</th>
                    <th>Is Voided</th>
                    <th>Bale</th>
                    <th>Cost</th>
                    <th>Date Received</th>
                    <th>Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                @forelse ($receivedProducts as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->supplier }}</td>
                        <td>{{ $product->product_sku }}</td>
                        <td>{{ $product->quantity_received }}</td>
                        <td>{{ $product->printed_barcodes ? 'Yes' : 'No' }}</td>
                        <td>{{ $product->is_voided ? 'Yes' : 'No' }}</td>
                        <td>{{ $product->bale }}</td>
                        <td>₱{{ number_format($product->cost, 2) }}</td>
                        <td>{{ $product->createdAt->format('M d, Y h:i A') }}</td>
                        <td>
                            @if($product->is_voided)
                                <span class="text-danger">Voided</span>
                            @else
                                <!-- Action Buttons -->
                                <div class="btn-group" role="group">
                                    <!-- Void Form -->
                                    <form action="{{ route('receivedProducts.void', $product->id) }}" method="POST" class="action-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to void this product?')">Void</button>
                                    </form>

                                    @if($product->printed_barcodes)
                                        <a href="{{ route('product-barcodes.index', ['product_sku' => $product->product_sku, 'received_product_id' => $product->id]) }}" class="btn btn-info btn-sm">See Barcodes</a>
                                    @else
                                        <form action="{{ route('receivedProducts.generateBarcodes', $product->id) }}" method="POST" class="action-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Generate Barcodes</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No received products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/receivedProductList.css?v=1.3') }}">
@endsection
