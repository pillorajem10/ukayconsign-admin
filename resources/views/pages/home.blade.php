@extends('layout')

@section('title', 'Home')

@section('content')
    <div class="upper-container mb-3">
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-gradient">Create New Product</a>
        </div>

        <div>
            <form action="{{ route('products.index') }}" method="GET" class="form-inline">
                <button type="submit" class="btn btn-custom-search mr-2">Search</button>
                <a href="{{ route('products.index') }}" class="btn btn-custom-search mr-2">Clear Search</a>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Product ID" aria-label="Search">
            </form>
        </div>        
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
                    {{--<th>Cost</th>--}}
                    <th>Stock</th>
                    {{--<th>Supplier</th>--}}
                    <th>Image</th>
                    <th>Actions</th>
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
                        {{--<td>{{ number_format($product->Cost ?? 0, 2) }}</td>--}}
                        <td>{{ $product->Stock ?? 'N/A' }}</td>
                        {{--<td>{{ $product->Supplier ?? 'N/A' }}</td>--}}
                        <td>
                            @if($product->Image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($product->Image) }}" alt="Product Image" class="product-image">
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="button-stack">
                            <form action="{{ route('products.destroy', $product->SKU) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            {{--<a href="{{ route('product-barcodes.index', ['product_sku' => $product->SKU]) }}" class="btn btn-info">See Barcodes</a>--}}
                            <a href="{{ route('receivedProducts.create', ['product_sku' => $product->SKU]) }}" class="btn btn-secondary">Receive Product</a>
                        </td>
                                                                                            
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>        
    </div>

    <!-- public/js/product.js  -->
    <script src="{{ asset('js/product.js?v=1.7') }}"></script>  
@endsection

@section('styles')

    <!-- public/css/homePage.css  -->
    <link rel="stylesheet" href="{{ asset('css/homePage.css?v=1.7') }}">
@endsection
