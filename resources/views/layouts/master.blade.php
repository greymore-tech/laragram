<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laragram') }}</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- NEW NAVBAR STRUCTURE -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <a href="{{ url('dashboard') }}" class="navbar-brand">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Laragram</span>
            </a>
        </div>
        <div class="navbar-right">
            @if(isset($current_user_first_name))
                <span class="navbar-user">Hello, {{ $current_user_first_name }}</span>
                <a href="{{ url('logout') }}" class="navbar-action" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
            @else
                <a href="{{ url('login') }}" class="navbar-action">Login</a>
            @endif
            <button id="theme-toggle" class="navbar-action" title="Toggle Theme">
                <i class="fa-solid fa-moon"></i>
            </button>
        </div>
    </nav>

    <div id="app">
        @yield('content')
    </div>
</body>
</html>
