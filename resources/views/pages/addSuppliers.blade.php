@extends('layout')

@section('title', 'Add Supplier')

@section('content')
    <div class="mb-3 text-right">
        <a href="{{ route('suppliers.index') }}" class="btn btn-gradient">View Suppliers</a>
    </div>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h5>Add New Supplier</h5>
            </div>
            <div class="form-body">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="supplier_name">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Supplier</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/addSupplier.css?v=2.3') }}">
@endsection
