@extends('layout')

@section('content')
<style>
    <style>
    .card:hover {
        transform: translateY(-3px);
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
</style>
</style>
<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Overview Cards -->
    <div class="row text-center g-4">
        <!-- Total Students -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background-color: #0f9100;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-people-fill fs-1 mb-3"></i>
                    <h5 class="card-title">Total Students</h5>
                    <a href="{{ route('student.list') }}" class="fs-4 fw-bold text-white text-decoration-none">{{ $total_student }}</a>
                </div>
            </div>
        </div>
    
        <!-- Total Exams -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background-color: #2e2eff;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-file-earmark-text fs-1 mb-3"></i>
                    <h5 class="card-title">Total Exams</h5>
                    <p class="fs-4 fw-bold">{{ $totalPapers }}</p>
                </div>
            </div>
        </div>
    
        <!-- Passed Students -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background-color: #9c27b0;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-check-circle-fill fs-1 mb-3"></i>
                    <h5 class="card-title">Passed Students Today</h5>
                    <p class="fs-4 fw-bold">{{ $passed }}</p>
                </div>
            </div>
        </div>
    
        <!-- Failed Students -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-white" style="background-color: #eb1111;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-x-circle-fill fs-1 mb-3"></i>
                    <h5 class="card-title">Failed Students Today</h5>
                    <p class="fs-4 fw-bold">{{ $failed }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="mt-4 col-md-6">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Exam attempt today</h5>
                    <ul class="list-group">
                        @foreach ($recentStudentsAttenpts as $studentAttemp)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $studentAttemp->user->name }}</span>
                                
                                @if (count($studentAttemp->examAttempts) > 1)
                                    <span class="badge bg-primary">Multiple attempts today</span>
                                @elseif (count($studentAttemp->examAttempts) == 1)
                                    <span class="badge bg-primary">{{ $studentAttemp->examAttempts->first()->created_at->format("H:i A") }}</span>
                                @else
                                    <span class="badge bg-danger">Not attempt today</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                
            </div>
        </div>
        <div class="mt-4 col-md-6" >

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
    

    <!-- Recent Student Registrations -->
    
</div>
@endsection
