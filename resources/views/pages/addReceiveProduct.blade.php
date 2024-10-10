@extends('layout')

@section('title', 'Receive Product')

@section('content')
<div>
    <h1>Receive Product</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('receivedProducts.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="supplier">Supplier</label>
            <input type="text" name="supplier" id="supplier" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="bale">Bale</label>
            <input type="text" name="bale" id="bale" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="quantity_received">Quantity Received</label>
            <input type="number" name="quantity_received" id="quantity_received" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="cost">Cost</label>
            <input type="number" name="cost" id="cost" class="form-control" step="0.01" required>
        </div>

        <input type="hidden" name="printed_barcodes" value="0"> <!-- default false -->
        <input type="hidden" name="is_voided" value="0"> <!-- default false -->
        <input type="hidden" name="batch_number" value=""> <!-- no input field -->
        <input type="hidden" name="product_sku" value="{{ old('product_sku', $productSku ?? '') }}"> <!-- hidden SKU field -->

        <button type="submit" class="btn btn-primary">Receive Product</button>
    </form>
</div>
@endsection
