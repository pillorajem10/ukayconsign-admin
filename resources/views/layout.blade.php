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
            position: relative;
            z-index: 1000;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
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

        .drawer {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: darkgreen;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            z-index: 999; /* Ensure it overlays content */
        }

        .drawer a {
            padding: 8px 8px;
            text-decoration: none;
            font-size: 1rem;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .drawer a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .drawer .close-btn {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 36px;
            color: white;
            background: transparent;
            border: none;
        }

        .content {
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            min-height: calc(100% - 56px);
            position: relative;
            z-index: 1; /* Make sure content is above drawer */
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

        .drawer-open {
            width: 250px; /* Width of the drawer */
        }

        /* No shift of content when the drawer is open, just overlap */
        .drawer-open ~ .content {
            z-index: 1; /* Make sure content stays above drawer */
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar with hamburger icon -->
    <nav class="navbar">
        <a class="navbar-brand" href="/">USC Admin</a>
        <button class="navbar-toggler" type="button" onclick="toggleDrawer()">
            <div class="navbar-toggler-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
    </nav>

    <!-- Drawer Menu -->
    <div id="drawer" class="drawer">
        <button class="close-btn" onclick="toggleDrawer()">&times;</button>
        <a href="/dashboard">Dashboard</a>
        <a href="/users">Users</a>
        <a href="/orders">Transaction</a>
        <a href="/received-products">Received Products</a>
        <a href="/suppliers">Suppliers</a>
        <a href="/products/inventory">USC Inventory</a>
        <a href="/stores">Stores</a>
        <a href="/store-inventory">Stores Inventory</a>
        <a href="/usc-returns">Returned Items</a>
        <a href="/billings">Billings</a>
        <a href="/manual">Manual</a>
        <a href="/pos">POS</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} Ukay Supplier. All Rights Reserved.
    </footer>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleDrawer() {
            document.getElementById('drawer').classList.toggle('drawer-open');
        }
    </script>
</body>
</html>
