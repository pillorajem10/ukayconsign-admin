@extends('layout')

@section('title', 'Home')

@section('content')
    <div>
        <table class="table table-bordered table-striped table-hover w-100">
            <thead class="thead-dark">
                <tr>
                    <th>SKU</th>
                    <th>Bundle</th>
                    <th>Product ID</th>
                    <th>Type</th>
                    <th>Style</th>
                    <th>Color</th>
                    <th>Gender</th>
                    <th>Category</th>
                    <th>Consign</th>
                    <th>Bundle Qty</th>
                    <th>Cost</th>
                    <th>Stock</th>
                    <th>Supplier</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->SKU ?? 'N/A' }}</td>
                        <td>{{ $product->Bundle ?? 'N/A' }}</td>
                        <td>{{ $product->ProductID ?? 'N/A' }}</td>
                        <td>{{ $product->Type ?? 'N/A' }}</td>
                        <td>{{ $product->Style ?? 'N/A' }}</td>
                        <td>{{ $product->Color ?? 'N/A' }}</td>
                        <td>{{ $product->Gender ?? 'N/A' }}</td>
                        <td>{{ $product->Category ?? 'N/A' }}</td>
                        <td>{{ $product->Consign !== null ? number_format($product->Consign, 2) : 'N/A' }}</td>
                        <td>{{ $product->Bundle_Qty ?? 'N/A' }}</td>
                        <td>{{ number_format($product->Cost ?? 0, 2) }}</td>
                        <td>{{ $product->Stock ?? 'N/A' }}</td>
                        <td>{{ $product->Supplier ?? 'N/A' }}</td>
                        <td>
                            @if($product->Image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($product->Image) }}" alt="Product Image" style="width: 100px; height: auto;">
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
