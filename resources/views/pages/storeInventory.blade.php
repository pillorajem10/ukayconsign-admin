@extends('layout')

@section('title', 'Stores Inventory')

@section('content')
<div>
    <h1 class="my-4">Store Inventory List</h1>

    <!-- Store Selection Form -->
    <form method="GET" action="{{ request()->url() }}" class="store-select-form mb-3">
        <div class="input-group">
            <select name="store_id" class="form-control" onchange="this.form.submit()">
                <option value="all">All Stores</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->store_name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="search" placeholder="Search by Product ID" class="form-control" value="{{ request()->get('search') }}" />
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('store-inventory.index') }}" class="btn clear-button">Clear</a> <!-- Clear button -->
        </div>
    </form>

    <!-- Table Wrapper for Overflow -->
    <div class="table-responsive overflow-auto">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product ID</th>
                    <th>Stocks</th>
                    <th>Consign</th>
                    <th>SRP</th>
                    <th>Store ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory as $item)
                <tr>
                    <td>{{ $item->SKU }}</td>
                    <td>{{ $item->ProductID }}</td>
                    <td>{{ $item->Stocks }}</td>
                    <td>{{ $item->Consign }}</td>
                    <td>{{ $item->SPR }}</td>
                    <td>{{ $item->store_id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $inventory->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>
    </div>
</div>

<!-- Link to CSS with cache-busting version -->
<link rel="stylesheet" href="{{ asset('css/storeInv.css?v=2.2') }}">
@endsection
