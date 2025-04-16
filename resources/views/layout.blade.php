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
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @auth
        @include('navbar')
    @endauth
    
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
            $("#subject_title").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            var csrf=$('meta[name="csrf-token"]').attr('content');
            

            if(value.length==0){
                $("#subjectList").empty();
                return;
            }
            $.ajax({
                url: `{{ route('subject_title.search') }}`,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'content-type': 'application/json'
                },
                data: {
                    search: value
                },
                success: function (response) {
                    console.log(response.data);
                    let data=response.data;
                    $("#subjectList").empty();

                    data.forEach(element => {
                        $("#subjectList").append(`<button type='button'  class="list-group-item list-group-item-action">${element.subject_name}</button>`);
                    });
                    
                }
            });
            });
            $("#subjectList").on("click","button",function(){
                $("#subject_title").val($(this).text());
                $("#subjectList").empty();
            });
            });
        
    </script>
</body>
</html>
