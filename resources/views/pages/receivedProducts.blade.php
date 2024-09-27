@extends('layout')

@section('title', 'Received Products')

@section('content')
    <div>
        <h1 class="text-center">Received Products</h1>

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
                    <th>Batch Number</th>
                    <th>Cost</th>
                    <th>Created At</th>
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
                        <td>{{ $product->batch_number }}</td>
                        <td>₱{{ number_format($product->cost, 2) }}</td>
                        <td>{{ $product->createdAt }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No received products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
