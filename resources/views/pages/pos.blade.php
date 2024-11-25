@extends('layout')

@section('title', 'POS')

@section('content')
    <div>
        <h1 class="page-title">Admin POS</h1>
        
        @if(session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="action-select">
            <label for="actionSelect">Select Action:</label>
            <select id="actionSelect" name="action" onchange="updateSelectedAction()">
                <option value="price-check" {{ $selectedAction === 'price-check' ? 'selected' : '' }}>Track Barcode</option>
                <option value="pos" {{ $selectedAction === 'pos' ? 'selected' : '' }}>Return to store</option>
            </select>                       
        </div>        
        
        <form method="POST" action="{{ route('pos.index') }}" id="barcodeForm" onsubmit="updateSelectedAction()">
            @csrf
            <input type="hidden" name="action" id="actionInput" value="{{ $selectedAction }}">
            <input type="text" name="barcode_number" id="barcodeNumberField" placeholder="Enter Barcode Number" required class="form-input">
            <button type="submit" class="form-button">Get Barcode Details</button>
        </form>                           

        <button id="scanBarcodeButton" class="form-button">Activate Camera For Barcode</button>

        <div id="cameraContainer" style="display:none; position:relative;">
            <div id="videoContainer"></div>
        </div>

        <div id="productDetails">
            @if(isset($productDetails))
                @if(is_array($productDetails) && isset($productDetails['message']))
                    <p class="message">{{ $productDetails['message'] }}</p>
                @endif
            @endif
        </div>

        <div id="productDetails" style="{{ $selectedAction === 'pos' ? 'display: none;' : '' }}">
            @if(isset($productDetails) && $productDetails)
                <h2 class="inventory-title">Barcode Tracker</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Barcode Number</th>
                            <th>Product ID</th>
                            <th>Bale</th>
                            <th>Barcode Location</th>
                            <th>SRP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $barcodeDetails->barcode_number }}</td>
                            <td>{{ $productDetails->ProductID }}</td>
                            <td>{{ $barcodeDetails->bale_received }}</td>
                            <td>{{ $barcodeDetails->barcode_location }}</td>
                            <td>₱{{ number_format($barcodeDetails->product_retail_price, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
        
        <div id="posCartDetails" style="{{ $selectedAction === 'price-check' ? 'display: none;' : '' }}">
            <h2 class="inventory-title">Return To Store Cart</h2>
            @if(!empty($posCarts))
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posCarts as $cart)
                            <tr>
                                <td>{{ $cart['product_bundle_id'] }}</td>
                                <td>{{ $cart['quantity'] }}</td>
                                <td>
                                    <form method="POST" action="{{ route('pos.void') }}">
                                        @csrf
                                        <input type="hidden" name="product_sku" value="{{ $cart['product_sku'] }}">
                                        <button type="submit" class="void-button">Void</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form method="POST" action="{{ route('pos.completeTransfer') }}">
                    @csrf
                    <button type="submit" class="form-button complete-button" onclick="return confirm('Are you sure you want to transfer these products? This action cannot be undone.')">Complete Transfer</button>
                </form>
            @else
                <p>No items in the cart.</p>
            @endif
        </div>
            
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
        <script src="{{ asset('js/pos.js?v=2.7') }}"></script>
        <script>
            // Pass PHP values to JavaScript variables
            const totalAmount = {{ json_encode($posCarts->sum('sub_total')) }};
        </script>     
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css?v=2.7') }}">
@endsection
