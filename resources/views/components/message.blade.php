@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif