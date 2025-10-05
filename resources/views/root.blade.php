<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dual Agent Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Assets with versioning -->
    <link rel="stylesheet" href="{{ asset('vendor/dual-agent-ui/build/app.css') }}?v={{ filemtime(public_path('vendor/dual-agent-ui/build/app.css')) }}">
    
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
    
    <!-- Scripts -->
    <script src="{{ asset('vendor/dual-agent-ui/build/app.js') }}?v={{ filemtime(public_path('vendor/dual-agent-ui/build/app.js')) }}" defer></script>
</body>
</html>