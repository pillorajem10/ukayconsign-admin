@extends('layout')

@section('title', 'Home')

@section('content')
    <div class="mb-3 text-right">
        <a href="{{ route('products.create') }}" class="btn btn-gradient">Create New Product</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div id="success-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover w-100 custom-table">
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
                    <th>Actions</th> <!-- Add an Actions column -->
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <!-- Existing columns here -->
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
                                <img src="data:image/jpeg;base64,{{ base64_encode($product->Image) }}" alt="Product Image" class="product-image">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <!-- Delete button -->
                            <form action="{{ route('products.destroy', $product->SKU) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>                                                      
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('js/product.js') }}"></script>  
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/homePage.css') }}">
@endsection
