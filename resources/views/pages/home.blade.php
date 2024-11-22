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
                <a href="{{ route('products.index') }}?search=&page={{ request()->input('page', 1) }}" class="btn btn-custom-search mr-2">Clear Search</a>
                <input type="text" name="search" value="{{ session('search') }}" class="form-control" placeholder="Search by Product ID" aria-label="Search">
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
                    <th>Category</th>
                    <th>Consign</th>
                    <th>SRP</th>
                    <th>Bundle Qty</th>
                    <th>Stock</th>
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
                        <td>{{ $product->Category ?? 'N/A' }}</td>
                        <td>{{ $product->Consign !== null ? number_format($product->Consign, 2) : 'N/A' }}</td>
                        <td>{{ $product->SRP ?? 'N/A' }}</td>
                        <td>{{ $product->Bundle_Qty ?? 'N/A' }}</td>
                        <td>{{ $product->Stock ?? 'N/A' }}</td>
                        <td>
                            @if($product->Image)
                                <img src="data:image/jpeg;base64,{{ base64_encode($product->Image) }}" alt="Product Image" class="product-image">
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="button-stack">
                            <form action="{{ route('products.destroy', $product->SKU) }}" method="POST" id="delete-form-{{ $product->SKU }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">Delete</button>
                            </form>
                            <a href="{{ route('receivedProducts.create', ['product_sku' => $product->SKU]) }}" class="btn btn-secondary">Receive Product</a>
                            <a href="{{ route('products.edit', $product->SKU) }}" class="btn btn-warning">Update</a>
                        </td>                                                                                    
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $products->appends(['search' => session('search'), 'page' => session('page')])->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>        
    </div>

    <!-- public/js/product.js  -->
    <script src="{{ asset('js/product.js?v=2.6') }}"></script>  
@endsection

@section('styles')

    <!-- public/css/homePage.css  -->
    <link rel="stylesheet" href="{{ asset('css/homePage.css?v=2.6') }}">
@endsection
