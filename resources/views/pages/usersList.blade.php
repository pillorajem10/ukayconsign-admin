@extends('layout')

@section('title', 'User List')

@section('content')
    <div>
        <h1 class="text-center mb-4 user-list-title">User List</h1>

        <form method="GET" action="{{ route('users.index') }}" class="mb-4 user-search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control user-search-input" placeholder="Search by email" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary user-search-button">Search</button>
                </div>
            </div>
        </form>

        @if ($users->isEmpty())
            <div class="alert alert-warning text-center user-no-results">No users found.</div>
        @else
            <div class="table-responsive">
                <table class="table user-list-table">
                    <thead>
                        <tr>
                            <th class="user-list-header">ID</th>
                            <th class="user-list-header">Name</th>
                            <th class="user-list-header">Email</th>
                            <th class="user-list-header">Actions</th> <!-- New column for actions -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="user-list-row">
                                <td>{{ $user->id }}</td>
                                <td>
                                    @if (is_null($user->fname) && is_null($user->lname))
                                        <em class="user-no-name">No Name</em>
                                    @else
                                        {{ $user->fname }} {{ $user->lname }}
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-success btn-sm">View</a> <!-- View button -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/usersList.css?v=2.2') }}">
@endsection
