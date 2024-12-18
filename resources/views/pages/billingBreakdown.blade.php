@extends('layout') <!-- Extending the main layout file -->

@section('title', 'Billing Breakdown')

@section('content')
    <div class="billing-breakdown-container">
        <h1 class="billing-breakdown-title">Billing Breakdown for Billing ID: {{ $billing->id }}</h1>

        <div class="back-to-dashboard">
            <a href="{{ route('billings.index') }}" class="btn btn-secondary">Back To Billings</a>
        </div>
        
        <div class="billing-info">
            <p><strong>Issued On:</strong> {{ $billing->bill_issued }}</p>
            <p>
                <strong>Issued To:</strong> 
                {{ $billing->user->fname ? $billing->user->fname . ' ' . $billing->user->lname : $billing->user->email }}
            </p>
            <p><strong>Sale Date Range:</strong> {{ $billing->sales_date_range }}</p>
            <p><strong>Status:</strong> {{ $billing->status }}</p>
            <p><strong>Total Bill:</strong> ₱{{ number_format($billing->total_bill, 2) }}</p>
        </div>

        <h3 class="payment-info-title">Payment Information:</h3>
        <div class="payment-info">
            <p><strong>Payment Platform:</strong> {{ $billing->payment_platform ?: 'N/A' }}</p>
            
            <p><strong>Proof of Payment:</strong></p>
            @if ($billing->proof_of_payment)
                @php
                    $proofType = mime_content_type('data://text/plain;base64,' . $billing->proof_of_payment);
                @endphp

                @if(in_array($proofType, ['image/jpeg', 'image/png', 'image/jpg']))
                    <img class="proof-img" src="data:{{ $proofType }};base64,{{ $billing->proof_of_payment }}" alt="Proof of Payment"/>
                @elseif($proofType == 'application/pdf')
                    <a href="data:application/pdf;base64,{{ $billing->proof_of_payment }}" target="_blank" class="proof-link">View PDF</a>
                @else
                    <span>File format not supported</span>
                @endif
            @else
                <span>N/A</span>
            @endif
        </div>

        <h3 class="breakdown-title">Breakdown:</h3>
        @if ($billingBreakdown && count($billingBreakdown) > 0)
            <div class="table-container">
                <table class="billing-breakdown-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billingBreakdown as $item)
                            <tr>
                                <td>{{ $item['product_bundle_id'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>₱{{ number_format($item['sub_total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="no-records">No items found in the billing breakdown.</p>
        @endif

                <!-- Mark as Paid Button -->
        @if ($billing->status !== 'Paid') <!-- Only show the button if the billing is not already marked as paid -->
            <form action="{{ route('billings.updatePayment', $billing->id) }}" method="POST" style="margin-top: 20px;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-primary">Mark As Paid</button>
            </form>
        @else
            <p><strong>Status:</strong> Paid</p>
        @endif
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billingBreakdown.css?v=2.8') }}">
@endsection
