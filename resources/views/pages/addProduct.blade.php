@extends('layout')

@section('title', 'Add Product')

@section('content')
    <div>
        <h1 class="text-center mb-4">Add New Product</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="SKU">SKU</label>
                        <input type="text" class="form-control" id="SKU" name="SKU" required>
                    </div>
                    <div class="form-group">
                        <label for="Bundle">Bundle</label>
                        <input type="text" class="form-control" id="Bundle" name="Bundle">
                    </div>
                    <div class="form-group">
                        <label for="Type">Type</label>
                        <input type="text" class="form-control" id="Type" name="Type">
                    </div>
                    <div class="form-group">
                        <label for="Style">Style</label>
                        <input type="text" class="form-control" id="Style" name="Style">
                    </div>
                    <div class="form-group">
                        <label for="Color">Color</label>
                        <input type="text" class="form-control" id="Color" name="Color">
                    </div>
                    <div class="form-group">
                        <label for="Gender">Gender</label>
                        <input type="text" class="form-control" id="Gender" name="Gender">
                    </div>
                    <div class="form-group">
                        <label for="Category">Category</label>
                        <input type="text" class="form-control" id="Category" name="Category">
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
                        <label for="Img_color">Image Color</label>
                        <input type="text" class="form-control" id="Img_color" name="Img_color">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Bundle_Qty">Bundle Qty</label>
                        <input type="number" class="form-control" id="Bundle_Qty" name="Bundle_Qty">
                    </div>
                    <div class="form-group">
                        <label for="Consign">Consign</label>
                        <input type="number" step="0.01" class="form-control" id="Consign" name="Consign">
                    </div>
                    <div class="form-group">
                        <label for="SRP">SRP</label>
                        <input type="text" class="form-control" id="SRP" name="SRP">
                    </div>
                    <div class="form-group">
                        <label for="maxSRP">Max SRP</label>
                        <input type="text" class="form-control" id="maxSRP" name="maxSRP">
                    </div>
                    <div class="form-group">
                        <label for="PotentialProfit">Potential Profit</label>
                        <input type="number" class="form-control" id="PotentialProfit" name="PotentialProfit">
                    </div>
                    <div class="form-group">
                        <label for="Cost">Cost</label>
                        <input type="number" step="0.01" class="form-control" id="Cost" name="Cost">
                    </div>
                    <div class="form-group">
                        <label for="Stock">Stock</label>
                        <input type="number" class="form-control" id="Stock" name="Stock">
                    </div>
                    <div class="form-group">
                        <label for="Supplier">Supplier</label>
                        <select class="form-control" id="Supplier" name="Supplier" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Bale">Bale</label>
                        <input type="text" class="form-control" id="Bale" name="Bale">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Product</button>
        </form>
    </div>

    <div id="snackbar"></div>

    <script src="{{ asset('js/product.js?v=1.1') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/addProduct.css?v=1.1') }}">
@endsection
