@extends('layout')

@section('title', 'Billing')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="billing-container">
        <h1 class="billing-title">Billing Records</h1>

        <div class="back-to-dashboard mb">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back To Home</a>
            <a href="{{ route('billings.index') }}" class="btn btn-secondary">Clear Filter</a>
        </div>

        <!-- Date Range Filter Form -->
        <form method="GET" action="{{ route('billings.index') }}" class="date-filter-form mb-4 mt-4">
            <div class="form-row">
                <div class="col-md-5">
                    <input type="date" name="start_date" class="form-control mb-4" value="{{ request('start_date') }}" placeholder="Start Date">
                </div>
                <div class="col-md-5">
                    <input type="date" name="end_date" class="form-control mb-4" value="{{ request('end_date') }}" placeholder="End Date">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        @if($billings->isEmpty())
            <p class="no-records">No billing records found.</p>
        @else
            <div class="table-container">
                <table class="billing-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Total Bill</th>
                            <th>Issued To</th>
                            <th>Status</th>
                            <th>Issued On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billings as $billing)
                            <tr>
                                <td>{{ $billing->id }}</td>
                                <td>₱{{ number_format($billing->total_bill, 2) }}</td>
                                <td>
                                    {{ $billing->user->fname ? $billing->user->fname . ' ' . $billing->user->lname : $billing->user->email }}
                                </td>
                                <td>{{ $billing->status }}</td>
                                <td>{{ \Carbon\Carbon::parse($billing->bill_issued)->format('M. d, Y') }}</td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('billings.show', $billing->id) }}" class="action-btn">View Breakdown</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{ $billings->appends(['start_date' => request('start_date'), 'end_date' => request('end_date')])->links('vendor.pagination.bootstrap-4') }}
                </ul>
            </nav>
        @endif
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css?v=2.8') }}">
@endsection
