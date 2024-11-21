@extends('layout')

@section('title', 'Stores')

@section('content')
<div class="store-list-container">
    <h1 class="store-list-title">Store List</h1>
    <div class="table-container">
        <table class="table store-table">
            <thead>
                <tr>
                    <th class="table-header">Name</th>
                    <th class="table-header">Owner</th>
                    {{--<th class="table-header">Address</th>--}}
                    <th class="table-header">Phone Number</th>
                    {{--<th class="table-header">Email</th>--}}
                    <th class="table-header">Total Earnings</th>
                    {{--<th class="table-header">Status</th>--}}
                    <th class="table-header">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                <tr class="table-row">
                    <td class="table-cell">{{ $store->store_name }}</td>
                    <td class="table-cell">{{ optional($store->user)->fname ?: 'No First Name' }} {{ optional($store->user)->lname ?: 'No Last Name' }}</td>
                    {{--<td class="table-cell">{{ $store->store_address }}</td>--}}
                    <td class="table-cell">{{ $store->store_phone_number }}</td>
                    {{--<td class="table-cell">{{ $store->store_email }}</td>--}}
                    <td class="table-cell">{{ $store->store_total_earnings }}</td>
                    {{--<td class="table-cell">{{ $store->store_status }}</td>--}}
                    <td class="table-cell">
                        <a href="{{ url('/store-inventory?store_id=' . $store->id) }}" class="btn btn-info">View Inventory</a>
                    </td>
                </tr>
                @endforeach
            </tbody>              
        </table>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/stores.css?v=2.5') }}">
@endsection
