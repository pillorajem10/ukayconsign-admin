@extends('layout')

@section('title', 'Suppliers')

@section('content')
    <div class="mb-3 text-right">
        <a href="{{ route('suppliers.create') }}" class="btn btn-gradient">Add Supplier</a>
    </div>
    
    @if(session('success'))
        <div id="success-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover w-100 custom-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->id }}</td>
                        <td class="supplier-name-cell" data-id="{{ $supplier->id }}">
                            <span class="supplier-name">{{ $supplier->supplier_name }}</span>
                            <input type="text" value="{{ $supplier->supplier_name }}" class="form-control supplier-name-input" data-id="{{ $supplier->id }}" style="display: none;">
                        </td>
                        <td>{{ $supplier->createdAt }}</td>
                        <td>{{ $supplier->updatedAt }}</td>
                        <td>
                            <button class="btn btn-success edit-button" data-id="{{ $supplier->id }}">Edit</button>
                            <button class="btn btn-success save-button" data-id="{{ $supplier->id }}" style="display: none;">Save</button>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('js/supplier.js?v=2.6') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/suppliersPage.css?v=2.6') }}">
@endsection
