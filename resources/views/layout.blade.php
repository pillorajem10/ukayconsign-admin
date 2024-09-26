<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%; /* Full height */
            margin: 0; /* Remove default margin */
        }

        .navbar-toggler {
            border: none; /* Remove default border */
            outline: none; /* Remove outline on focus */
        }

        .navbar-toggler-icon {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            transition: opacity 0.3s ease; /* Smooth transition */
        }

        .navbar-toggler-icon span {
            display: block;
            width: 100%;
            height: 4px;
            background-color: white; /* Change to white */
            border: 1px solid white; /* Add border to each bar */
            margin: 2px 0; /* Space between bars */
            transition: all 0.3s ease;
        }

        /* Transform the burger into an X */
        .navbar-toggler.collapsed .bar1 {
            transform: rotate(0);
        }
        .navbar-toggler.collapsed .bar2 {
            opacity: 1; /* Show middle bar when collapsed */
        }
        .navbar-toggler.collapsed .bar3 {
            transform: rotate(0);
        }

        .navbar-toggler:not(.collapsed) .bar1 {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .navbar-toggler:not(.collapsed) .bar2 {
            opacity: 0; /* Hide middle bar when active */
        }
        .navbar-toggler:not(.collapsed) .bar3 {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* Remove outline on button focus */
        .navbar-toggler:focus {
            outline: none; /* Remove focus outline */
        }

        .content {
            min-height: calc(100% - 56px); /* Adjust based on navbar height */
            padding: 20px; /* Add some padding */
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #004d00;">
        <a class="navbar-brand text-light" href="/">MyApp</a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <div class="navbar-toggler-icon">
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </div>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link text-light" href="/">Home</a>
                </li>
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
