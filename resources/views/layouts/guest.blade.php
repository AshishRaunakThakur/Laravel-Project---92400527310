<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · CampusCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v=20261026-authfix2" rel="stylesheet">
</head>
<body class="guest-page">
    <header class="auth-site-header">
        <div class="auth-site-header-inner">
            <a class="site-logo" href="{{ route('home') }}" aria-label="Marwadi University home"><img src="{{ asset('images/marwadi-university-logo-naac.png') }}?v=1" alt="Marwadi University"></a>
            <nav class="auth-site-links" aria-label="Main navigation">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('login') }}">Login</a>
                <a class="auth-register-link" href="{{ route('register') }}">Register</a>
            </nav>
        </div>
    </header>
    <main class="guest-wrap @yield('wrap-class')">
        <section class="guest-card @yield('card-class')">@yield('content')</section>
    </main>
</body>
</html>
