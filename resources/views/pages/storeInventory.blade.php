@extends('layout')

@section('title', 'Stores Inventory')

@section('content')
    <div>
        <h1>Store Inventory List</h1>

        <!-- Store Selection Form -->
        <form method="GET" action="{{ request()->url() }}" class="store-select-form" id="storeSelectForm">
            <select name="store_id" class="store-select" onchange="this.form.submit()">
                <option value="">Select a Store</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ request()->get('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->store_name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" style="display: none;">Filter</button> <!-- Hidden filter button -->
        </form>

        <!-- Search Input Form -->
        <form method="GET" action="{{ request()->url() }}" class="search-form">
            <input type="text" name="search" placeholder="Search by Product ID" class="search-input" value="{{ request()->get('search') }}" />
            <button type="submit">Search</button>
            <a href="{{ route('store-inventory.index') }}" class="clear-button">Clear</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product ID</th>
                        <th>Stocks</th>
                        <th>Consign</th>
                        <th>SRP</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bootstrap Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $inventory->links('pagination::bootstrap-5') }}
        </div>

        <link rel="stylesheet" href="{{ asset('css/storeInv.css?v=1.5') }}">
    </div>
@endsection
