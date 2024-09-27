@extends('layout')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Welcome to your Dashboard, {{ Auth::user()->email }}!</h1>

        <h2 class="mt-4">User Details:</h2>
        <div class="card">
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
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
@endsection
