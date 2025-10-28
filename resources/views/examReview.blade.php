@extends('layout')

@section('content')

<div class="container mt-5">
    <div class="bg-light text-center p-4 rounded shadow-sm">
        <h4 class="mb-3">Exam Summary</h4>
        <p class="mb-1">You have completed the exam. Below is a summary of your performance:</p>
        <div class="d-flex justify-content-center mt-3 space-x-4">
            <div class="me-4">
                <h5 class="text-primary mb-0">{{ $total_questions }}</h5>
                <small>Total Questions</small>
            </div>
            <div class="me-4">
                <h5 class="text-success mb-0">{{ $correctAnswersCount }}</h5>
                <small>Correct Answers</small>
            </div>
            <div>
                <h5 class="text-danger mb-0">{{ $total_questions - $correctAnswersCount }}</h5>
                <small>Wrong Answers</small>
            </div>
            <div class="ms-4">
                <h5 class="text-info mb-0">{{ round(($correctAnswersCount / max(1,$total_questions)) * 100, 2) }}%</h5>
                <small>Score Percentage</small>
            </div>
        </div>
    </div>
    <h2 class="text-center">Review Your Answers</h2>
    
    @foreach ($exam_questions as $exam_question)
        <div class="card mb-3">
            <div class="card-header">
                
                {{-- <strong>Q{{ $loop->iteration }}: {{ $exam_question->subject->sub_title }}</strong> --}}
                <span>
                    <strong>Q{{ $loop->iteration }}:</strong>
                </span>
                @if (strpos($exam_question->subject->sub_title, "\n") !== false)
                    {!! nl2br(e($exam_question->subject->sub_title)) !!}
                @else
                    {{ $exam_question->subject->sub_title }}
                @endif
               
            </div>
            <div class="card-body">
                <div class="form-group">
                    @php
                        $content=json_decode($exam_question->subject->sub_content);
                        
                    @endphp
                    @if ($content->type=="picture")
                        <img src="{{ asset($content->content) }}" alt="preview image" style="display:block; max-width: 200px; max-height:150px; margin-top: 10px;" >
                    @endif
                </div>
                @foreach ($exam_question->subject->questions as $key => $option)

                    <div class="form-check">
                        <input class="form-check-input text-dark" type="radio" disabled 
                               @if(isset($studentAnswers[$exam_question->subject_id]) && $studentAnswers[$exam_question->subject_id] == $option->question_section) checked @endif>
                        <label class="form-check-label @if($option->question_section == $exam_question->subject->correct_ans) text-success fw-bold @endif">
                            {{ $option->question_title }}

                            @if($option->question_section == $exam_question->subject->correct_ans)
                                ✅ <small class="text-success">(Correct Answer)</small>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
