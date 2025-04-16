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
                <div class="form-group">
                    @php
                        if($exam_question->subject->sub_content!=""){
                            $content=json_decode($exam_question->subject->sub_content);
                        }
                        
                    @endphp
                    @if ($content!=="")
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
