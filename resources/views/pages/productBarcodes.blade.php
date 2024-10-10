@extends('layout')

@section('title', 'Product Barcodes')

@section('content')
    <div>
        <h1 class="text-center mb-4">Product Barcodes</h1>
        <button onclick="printTable()" class="btn btn-primary mb-3 print-button">Print</button>
        <div class="table-responsive" id="printableArea">
            <table class="table table-bordered barcode-table text-center">
                <tbody>
                    @foreach($barcodes as $index => $barcode)
                        @if($index % 3 == 0)
                            <tr>
                        @endif
                            <td class="barcode-cell" style="padding: 20px;">
                                @if($barcode->barcode_image)
                                    <img src="data:image/png;base64,{{ base64_encode($barcode->barcode_image) }}" alt="Barcode" class="barcode-image">
                                    <p class="barcode-number">{{ $barcode->barcode_number }}</p>
                                @else
                                    <span class="no-image">No Image</span>
                                @endif
                            </td>
                        @if($index % 3 == 2 || $loop->last)
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                {{ $barcodes->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </nav>  
    </div>
    <script src="{{ asset('js/barcodes.js?v=1.3') }}"></script>  
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/barcodes.css?v=1.3') }}">
@endsection
