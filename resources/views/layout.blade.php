<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        .navbar-toggler-icon {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            transition: opacity 0.3s ease;
        }

        .navbar-toggler-icon span {
            display: block;
            width: 100%;
            height: 4px;
            background-color: white;
            border: 1px solid white;
            margin: 2px 0;
            transition: all 0.3s ease;
        }

        .navbar-toggler.collapsed .bar1 {
            transform: rotate(0);
        }
        .navbar-toggler.collapsed .bar2 {
            opacity: 1;
        }
        .navbar-toggler.collapsed .bar3 {
            transform: rotate(0);
        }

        .navbar-toggler:not(.collapsed) .bar1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .navbar-toggler:not(.collapsed) .bar2 {
            opacity: 0;
        }
        .navbar-toggler:not(.collapsed) .bar3 {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .navbar-toggler:focus {
            outline: none;
        }

        .content {
            min-height: calc(100% - 56px);
            padding: 20px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #004d00;">
        <a class="navbar-brand text-light" href="/">Ukay Supplier Consign Admin</a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <div class="navbar-toggler-icon">
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </div>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @if (Auth::check()) <!-- Check if the user is logged in -->
                    <li class="nav-item active">
                        <a class="nav-link text-light" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link text-light" href="/products/create">Add Product</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link text-light" href="/orders">View Orders</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link text-light" href="/received-products">View Received Products</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
