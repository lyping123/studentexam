@extends('layout')

@section('content')

<div class="container mt-5">
    <h2 class="text-center">Review Your Answers</h2>
    
    @foreach ($exam_questions as $exam_question)
        <div class="card mb-3">
            <div class="card-header">
                
                <strong>Q{{ $loop->iteration }}: {{ $exam_question->subject->sub_title }}</strong>
            </div>
            <div class="card-body">
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
