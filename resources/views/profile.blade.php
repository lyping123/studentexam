@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4" style="background: linear-gradient(90deg, #007bff 60%, #0056b3 100%);">
                    <h3 class="mb-0 font-weight-bold">My Profile</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name ?? '' }}" >
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" >
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="changePasswordCheckbox" name="checkpassword" onclick="togglePasswordInput()">
                                <label class="form-check-label" for="changePasswordCheckbox">
                                    Change Password
                                </label>
                            </div>
                            <script>
                            function togglePasswordInput() {
                                const checkbox = document.getElementById('changePasswordCheckbox');
                                const passwordInput = document.getElementById('password');
                                if (checkbox.checked) {
                                    passwordInput.removeAttribute('readonly');
                                    passwordInput.value = '';
                                } else {
                                    passwordInput.setAttribute('readonly', true);
                                    passwordInput.value = '***********';
                                }
                            }
                            </script>

                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" id="password" value="***********" readonly>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-pencil-square me-2"></i>Save Change Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Optionally include Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection