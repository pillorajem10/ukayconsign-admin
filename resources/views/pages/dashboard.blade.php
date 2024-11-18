@extends('layout')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to your Dashboard, {{ Auth::user()->email }}!</h1>

        <h2 class="mt-4 text-center">User Details:</h2>
        <div class="card shadow-lg rounded dashboard-card">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">Email: {{ Auth::user()->email }}</li>
                    <li class="list-group-item">Role: {{ Auth::user()->role }}</li>
                    <li class="list-group-item">Verified: {{ Auth::user()->verified ? 'Yes' : 'No' }}</li>
                </ul>
            </div>
        </div>

        <div class="text-center mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-logout">Logout</button>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css?v=2.5') }}">
@endsection
