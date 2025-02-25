<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>student exam form</title>
    {{-- @vite(['resources/js/app.js', 'resources/css/app.css']) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @include('navbar')
    <div class="container mt-4">
        <!-- Success message -->
        <x-message />

        @yield('content')
    </div>
    <script>
        $(document).ready(function () {
            $("div.alert").on("click", function () {
             $(this).remove();
            });
        });
    </script>
</body>
</html>
