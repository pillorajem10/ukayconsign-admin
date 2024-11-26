@extends('layout')

@section('title', 'Product Barcodes')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Product Barcodes</h1>
        
        <!-- Print Button -->
        <button onclick="printBarcodes()" class="btn btn-primary mb-3 print-button">Print</button>

        <!-- Back to Received Products Button -->
        <a href="{{ route('receivedProducts.index') }}?page={{ session('received_products_page', 1) }}" class="btn btn-secondary mb-3">
            Back to Received Products
        </a>

        <!-- Barcode container for printing -->
        <div class="barcode-container" id="printableArea">
            @foreach($barcodes as $barcode)
                <div class="barcode-item">
                    @if($barcode->barcode_image)
                        <div class="barcode-number">
                            {{ $barcode->barcode_number }} - 
                            {{ $barcode->product ? $barcode->product->SRP : 'N/A' }}
                        </div>
                        <img src="data:image/png;base64,{{ base64_encode($barcode->barcode_image) }}" alt="Barcode" class="barcode-image">
                    @else
                        <span class="no-image">No Image</span>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination (won't be visible in print) -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $barcodes->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>

        <script src="{{ asset('js/barcodes.js?v=2.8') }}"></script>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/barcodes.css?v=2.8') }}">
@endsection
