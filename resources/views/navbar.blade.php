<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="https://via.placeholder.com/40" alt="Logo" class="me-2">Student Exam system
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('exam.index') }}">Upload question band</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Set question paper</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.showlog') }}">User log</a>
                </li>

                <!-- Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        More
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Blog</a></li>
                        <li><a class="dropdown-item" href="#">Contact</a></li>
                    </ul>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="btn btn-danger ms-3" href="{{ route('user.logout') }}">Logout</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-primary ms-3" href="{{ route('user.login') }}">Login</a>
                    </li>
                @endauth
                
            </ul>
        </div>
    </div>
</nav>
