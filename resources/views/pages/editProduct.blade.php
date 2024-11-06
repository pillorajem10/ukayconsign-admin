@extends('layout')

@section('title', 'Edit Product')

@section('content')
    <div>
        <h1 class="text-center mb-4">Edit Product</h1>
        <form action="{{ route('products.update', $product->SKU) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="SKU">SKU</label>
                        <input type="text" class="form-control" id="SKU" name="SKU" value="{{ $product->SKU }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="Bundle">Bundle</label>
                        <input type="text" class="form-control" id="Bundle" name="Bundle" value="{{ $product->Bundle }}">
                    </div>
                    <div class="form-group">
                        <label for="Type">Type</label>
                        <input type="text" class="form-control" id="Type" name="Type" value="{{ $product->Type }}">
                    </div>
                    <div class="form-group">
                        <label for="Style">Style</label>
                        <input type="text" class="form-control" id="Style" name="Style" value="{{ $product->Style }}">
                    </div>
                    <div class="form-group">
                        <label for="Color">Color</label>
                        <input type="text" class="form-control" id="Color" name="Color" value="{{ $product->Color }}">
                    </div>
                    <div class="form-group">
                        <label for="Gender">Gender</label>
                        <input type="text" class="form-control" id="Gender" name="Gender" value="{{ $product->Gender }}">
                    </div>
                    <div class="form-group">
                        <label for="Category">Category</label>
                        <select class="form-control" id="Category" name="Category">
                            <option value="">Select Category</option>
                            <option value="Exclusive" {{ $product->Category == 'Exclusive' ? 'selected' : '' }}>Exclusive</option>
                            <option value="Signature" {{ $product->Category == 'Signature' ? 'selected' : '' }}>Signature</option>
                            <option value="Essential" {{ $product->Category == 'Essential' ? 'selected' : '' }}>Essential</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Bundle_Qty">Bundle Qty</label>
                        <input type="number" class="form-control" id="Bundle_Qty" name="Bundle_Qty" value="{{ $product->Bundle_Qty }}">
                    </div>
                    <div class="form-group">
                        <label for="Consign">Consign</label>
                        <input type="number" step="0.01" class="form-control" id="Consign" name="Consign" value="{{ $product->Consign }}">
                    </div>
                    <div class="form-group">
                        <label for="SRP">SRP</label>
                        <input type="text" class="form-control" id="SRP" name="SRP" value="{{ $product->SRP }}">
                    </div>
                    <div class="form-group">
                        <label for="PotentialProfit">Potential Profit</label>
                        <input type="number" class="form-control" id="PotentialProfit" name="PotentialProfit" value="{{ $product->PotentialProfit }}">
                    </div>
                    <div class="form-group">
                        <label for="Image">Image</label>
                        <input type="file" class="form-control" id="Image" name="Image">
                    </div>
                    <div class="form-group">
                        <label for="Secondary_Img">Secondary Image</label>
                        <input type="file" class="form-control" id="Secondary_Img" name="Secondary_Img">
                    </div>

                    <div class="form-group">
                        <label for="details_images">Details Images</label>
                        <input type="file" name="details_images[]" id="details_images" class="form-control" accept="image/*" multiple>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update Product</button>
        </form>
    </div>

    <div id="snackbar"></div>

    <script src="{{ asset('js/product.js?v=2.2') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/addProduct.css?v=2.2') }}">
@endsection
