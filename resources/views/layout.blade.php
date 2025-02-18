<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Navbar</title>
    {{-- @vite(['resources/js/app.js', 'resources/css/app.css']) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <div class="container mt-4">
        <!-- Success message -->
        <x-message />
        @yield('content')
    </div>
</body>
</html>
