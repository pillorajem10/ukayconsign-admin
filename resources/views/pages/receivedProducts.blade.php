@extends('layout')

@section('title', 'Received Products')

@section('content')
<div class="container mt-4">
    <h1 class="text-center page-title">Received Products</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success message-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger message-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered mt-4 received-products-table">
            <thead>
                <tr class="table-header">
                    <th class="responsive-header">Supplier</th>
                    <th class="responsive-header">Product</th>
                    <th class="responsive-header">Quantity Received</th>
                    <th class="responsive-header">Bale</th>
                    <th class="responsive-header">Cost</th>
                    <th class="responsive-header">Date Received</th>
                    <th class="responsive-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($receivedProducts as $product)
                    <tr class="table-row">
                        <td class="responsive-cell">{{ $product->supplier }}</td>
                        <td class="responsive-cell">{{ $product->product_sku }}</td>
                        <td class="responsive-cell">{{ $product->quantity_received }}</td>
                        <td class="responsive-cell">{{ $product->bale }}</td>
                        <td class="responsive-cell">₱{{ number_format($product->cost, 2) }}</td>
                        <td class="responsive-cell">{{ $product->createdAt->format('M. d, Y') }}</td>
                        <td class="responsive-cell">
                            @if($product->is_voided)
                                <span class="text-danger voided-label">Voided</span>
                            @else
                                <div class="btn-group action-buttons" role="group">
                                    <!-- Void Form -->
                                    <form action="{{ route('receivedProducts.void', $product->id) }}" method="POST" class="action-form void-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm void-button" onclick="return confirm('Are you sure you want to void this product?')">Void</button>
                                    </form>

                                    @if($product->printed_barcodes)
                                        <a href="{{ route('product-barcodes.index', ['product_sku' => $product->product_sku, 'received_product_id' => $product->id]) }}" class="btn btn-info btn-sm view-barcodes-button">See Barcodes</a>
                                    @else
                                        <form action="{{ route('receivedProducts.generateBarcodes', $product->id) }}" method="POST" class="action-form generate-barcodes-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm generate-barcodes-button">Generate Barcodes</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="no-products-row">
                        <td colspan="7" class="text-center no-products-message">No received products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $receivedProducts->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav> 
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/receivedProductList.css?v=2.7') }}">
@endsection
