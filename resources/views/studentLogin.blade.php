@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h4>Student Login</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('student.login') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="ic" class="form-label">Ic</label>
                        <input type="ic" name="ic" id="ic" class="form-control" value="{{ old('ic') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection