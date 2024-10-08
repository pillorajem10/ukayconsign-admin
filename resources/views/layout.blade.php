<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        window.messages = {
            success: @json(session('success')),
            error: @json($errors->first())
        };
    </script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(90deg, #004d00, #007f00);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: white !important; /* Changed to white */
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: white !important;
            padding: 10px 15px;
            transition: background-color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
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
        }

        .navbar-toggler-icon span {
            display: block;
            width: 100%;
            height: 4px;
            background-color: white;
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
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        footer {
            background: linear-gradient(90deg, #004d00, #007f00);
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: auto;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="/">USC Admin</a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <div class="navbar-toggler-icon">
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </div>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @if (Auth::check()) <!-- Check if the user is logged in -->
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/orders">Transaction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/received-products">Received Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/suppliers">Suppliers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/stores">Stores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/store-inventory">Store Inventory</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="content">
        @yield('content')
    </div>

    <footer>
        &copy; {{ date('Y') }} Ukay Supplier. All Rights Reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
