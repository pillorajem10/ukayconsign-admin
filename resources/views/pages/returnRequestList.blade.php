@extends('layout')

@section('title', 'Return Request List')

@section('content')
    <div>
        {{-- Success and Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover return-request-table">
                <thead class="table-header">
                    <tr>
                        <th scope="col" class="table-cell">Return ID</th>
                        <th scope="col" class="table-cell">Product</th>
                        <th scope="col" class="table-cell">Quantity</th>
                        <th scope="col" class="table-cell">Store Name</th>
                        <th scope="col" class="table-cell">Status</th>
                        <th scope="col" class="table-cell">Date</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @foreach($returns as $return)
                        <tr class="table-row">
                            <td class="table-cell">{{ $return->id }}</td>
                            <td class="table-cell">{{ $return->product->ProductID ?? 'Unknown Product' }}</td>
                            <td class="table-cell">{{ $return->quantity }}</td>
                            <td class="table-cell">{{ $return->store->store_name ?? 'Unknown Store' }}</td>

                            {{-- Start of form for status update --}}
                            <td class="table-cell">
                                <form action="{{ route('usc-returns.updateStatus') }}" method="POST" class="status-form" id="statusForm_{{ $return->id }}">
                                    @csrf
                                    <input type="hidden" name="return_id" value="{{ $return->id }}">
                                
                                    <select class="form-control" name="return_status" id="status_{{ $return->id }}" onchange="this.form.submit()">
                                        <option value="Processing" {{ $return->return_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="Shipped" {{ $return->return_status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Received" {{ $return->return_status == 'Received' ? 'selected' : '' }}>Received</option>
                                        <option value="Packed Back To Store" {{ $return->return_status == 'Packed Back To Store' ? 'selected' : '' }}>Packed Back To Store</option>
                                        <option value="Shipped Back To Store" {{ $return->return_status == 'Shipped Back To Store' ? 'selected' : '' }}>Shipped Back To Store</option>
                                        <option value="Received By Store" {{ $return->return_status == 'Received By Store' ? 'selected' : '' }}>Received By Store</option>
                                    </select>                                                                   
                                </form>                                
                            </td>

                            <td class="table-cell">{{ \Carbon\Carbon::parse($return->created_at)->format('M. j, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls --}}
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $returns->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/returnRequestList.css?v=2.8') }}">
@endsection
