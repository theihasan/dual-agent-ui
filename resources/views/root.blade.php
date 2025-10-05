<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dual Agent Dashboard</title>
    
    <link rel="stylesheet" href="{{ asset('vendor/dual-agent-ui/build/app.css') }}">
    <script src="{{ asset('vendor/dual-agent-ui/build/app.js') }}" defer></script>
    
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>