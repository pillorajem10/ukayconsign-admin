@extends('layout')

@section('title', 'Quantity Sold Items')

@section('content')
    <div class="container">
        <h1>Quantity of Sold Items</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Consign</th>
                    <th>Cost</th>
                    @foreach($stores as $store)
                        <th>{{ $store->store_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row['product_name'] }}</td>
                        <td>{{ $row['consign'] }}</td>
                        <td>{{ number_format($row['highest_cost'], 2) }}</td>
                        @foreach($stores as $store)
                            <td>{{ $row[$store->store_name] }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <!-- Totals row -->
                <tr>
                    <td colspan="3"><strong>Total Sold Items</strong></td>
                    @foreach($stores as $store)
                        <td><strong>{{ $totals[$store->store_name] }}</strong></td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/qtySold.css?v=2.8') }}">
@endsection
