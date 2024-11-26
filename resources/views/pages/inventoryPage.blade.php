@extends('layout')

@section('title', 'Inventory')

@section('content')
    <div class="container">
        <h2 class="text-center">Product Inventory</h2>

        <!-- Search Form -->
        <form action="{{ route('products.showInventory') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request()->input('search') }}" placeholder="Search by Product ID">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Inventory Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->ProductID }}</td>
                            <td>{{ $product->Stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>
    </div>

    <script src="{{ asset('js/inventoryPage.js?v=2.8') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inventoryPage.css?v=2.8') }}">
@endsection
