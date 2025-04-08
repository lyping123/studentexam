@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Overview Cards -->
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <a href="{{ route('student.list') }}" class="fs-4">{{ $total_student }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Exams</h5>
                    <p class="fs-4">{{ $totalPapers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Passed Students today</h5>
                    <p class="fs-4 text-success">{{ $passed }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Failed Students today</h5>
                    <p class="fs-4 text-danger">{{ $failed }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Student Registrations -->
    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Recent Student Registrations</h5>
                <ul class="list-group">
                    @foreach ($recentStudents as $student)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $student->name }}</span>
                            <span class="badge bg-primary">{{ $student->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
