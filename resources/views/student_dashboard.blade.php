@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="position-sticky" style="top: 1rem;">
            <div class="card shadow-sm border-0 overflow-hidden">
                @php
                $user = auth()->user();
                $name = trim($user->name ?? '');
                $parts = preg_split('/\s+/', $name);
                $initials = '';
                foreach ($parts as $p) {
                    if ($p !== '') { $initials .= mb_strtoupper(mb_substr($p, 0, 1)); }
                }
                $initials = mb_substr($initials, 0, 2);
                $attemptsCount = isset($examAttenpts) ? count($examAttenpts) : 0;
                @endphp
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #4e54c8, #8f94fb);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center me-3 shadow-sm" style="width:64px;height:64px;font-weight:700;">
                    {{ $initials ?: 'U' }}
                    </div>
                    <div>
                    <div class="fw-semibold fs-5 mb-1">{{ $user->name }}</div>
                    <span class="badge bg-light text-primary">Student</span>
                    </div>
                </div>
                </div>
                <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Email</span>
                    <span class="text-muted">{{ optional($user->created_at)->format('d-m-Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Exams attempted</span>
                    <span class="text-muted">{{ $attemptsCount }}</span>
                    </li>
                </ul>
                </div>
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
                    <li class="list-group-item">Time duration:
                      @if(!is_null($upcomingExam->time_limit))
                        <strong>{{ $upcomingExam->time_limit }}</strong> Minute
                      @else
                        N/A
                      @endif
                    </li>
                    <li class="list-group-item">Date:
                      @if(!is_null($upcomingExam->start_datetime))
                        <strong>{{ $upcomingExam->start_datetime->format('d-m-Y') }}</strong>
                      @else
                        N/A
                      @endif
                    </li>
                    <li class="list-group-item">Start Time:
                      @if(!is_null($upcomingExam->start_datetime))
                        <strong>{{ $upcomingExam->start_datetime->format('H:i') }}</strong>
                      @else
                        N/A
                      @endif
                    </li>
                    <li class="list-group-item">Total question:
                      @if(!is_null($upcomingExam->total_question))
                        <strong>{{ $upcomingExam->total_question }}</strong>
                      @else
                        N/A
                      @endif
                    </li>
                    <li class="list-group-item">Created by:
                      @php $creatorName = optional(optional($upcomingExam->exam_question->first())->user)->name; @endphp
                      @if($creatorName)
                        <strong>{{ $creatorName }}</strong>
                      @else
                        N/A
                      @endif
                    </li>
                  </ul>
                </div>
              </div>
            </a> 
        </div>
        @endforeach
    </div>
    
</div>
@endsection
