@extends('layout')

@section('title', 'User Details')

@section('content')
<div id="imageModal" class="modal" onclick="this.style.display='none'">
    <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
    <img class="modal-content" id="modalImage">
</div>
<div class="container mt-5 user-details-container">
    <h1 class="text-center user-details-title">User Details</h1>
    <div class="card mt-4 user-details-card">
        <div class="card-body">
            <div>
                <h5 class="card-title user-info-title">User Information</h5>
                <p><strong>First Name:</strong> <span class="user-info">{{ $user->fname }}</span></p>
                <p><strong>Last Name:</strong> <span class="user-info">{{ $user->lname }}</span></p>
                <p><strong>Email:</strong> <span class="user-info">{{ $user->email }}</span></p>
                <p><strong>Phone Number:</strong> <span class="user-info">{{ $user->phone_number }}</span></p>
                <p><strong>Facebook Link:</strong> <a href="{{ $user->fb_link }}" target="_blank" class="user-link">{{ $user->fb_link }}</a></p>
                <p><strong>Estimated Items Sold Per Month:</strong> <span class="user-info">{{ $user->estimated_items_sold_per_month }}</span></p>
            </div>
            <div>
                <h5 class="mt-4 user-docs-title">Documents</h5>
                <p><strong>Government ID:</strong></p>
                <img src="data:image/jpeg;base64,{{ base64_encode($user->government_id_card) }}" alt="Government ID" class="img-fluid user-doc-image" onclick="openModal(this.src)" />

                <p><strong>Proof of Billing:</strong></p>
                <img src="data:image/jpeg;base64,{{ base64_encode($user->proof_of_billing) }}" alt="Proof of Billing" class="img-fluid user-doc-image" onclick="openModal(this.src)" />

                <p><strong>Selfie:</strong></p>
                <img src="data:image/jpeg;base64,{{ base64_encode($user->selfie_uploaded) }}" alt="Selfie" class="img-fluid user-doc-image" onclick="openModal(this.src)" />

                <div class="mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-primary user-back-button">Back to Users List</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/userDetails.js?v=2.1') }}"></script>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/userDetails.css') }}">
@endsection
