@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Profile</h5>
                    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Role:</strong> Student</p>
                </div>
            </div>
        </div>

        <!-- Exam Results -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recent Exam Results</h5>
                    <ul class="list-group">
                        @if (count($examAttenpts) == 0)
                            <li class="list-group-item">No data found</li>
                        @else
                        @foreach ($examAttenpts as $examAttenpt)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $examAttenpt->user->name }}</span>
                                <span>{{ $examAttenpt->question_paper->paper_name }}</span>
                                <span>{{ $examAttenpt->correct_answers."/".$examAttenpt->student_answer->count() }}</span>
                                <span>{{  round(($examAttenpt->correct_answers/max(1,$examAttenpt->student_answer->count()))*100,2).'%' }}</span>
                                <span>{{ $examAttenpt->created_at->format('d-m-Y') }}</span>
                                <a href="{{ route('demoexam.review', $examAttenpt->id) }}" class="badge bg-success "><span class="badge bg-success">View</span></a>
                            </li>
                    @endforeach
                        
                        @endif
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="row">
        @foreach ($upcomingExams as $upcomingExam)
        <div class="col-md-4 mt-4">
            <a href="{{ route('student.demoexam.index',encrypt($upcomingExam->id)) }}">
              <div class="card" style="width: 18rem;">
                <img src="{{ asset('img/paperexam.png') }}" class="card-img-top" alt="paper">
                <div class="card-body">
                  <h5 class="card-title"><strong>{{ $upcomingExam->paper_name }}</strong></h5>
                  <p class="card-text"></p>
                  {{-- <a href="{{ route('student.demoexam.index',encrypt($upcomingExam->id)) }}"></a> --}}
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Time duration: {{ $upcomingExam->time_limit ?? 'N/A' }} Minute</li>
                    <li class="list-group-item">Total question: {{ $upcomingExam->total_question ?? 'N/A' }} </li>
                    <li class="list-group-item">Created by: {{ $upcomingExam->exam_question->first()->user->name ?? 'N/A' }}</li>
                  </ul>
                </div>
              </div>
            </a> 
        </div>
        @endforeach
    </div>
    
</div>
@endsection
