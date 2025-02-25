@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Student Exam</h2>

    <form action="{{ route('demoexam.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="paper_id" value="{{ $question_paper->id }}">

        @foreach ($exam_questions as $question)
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Q{{ $loop->iteration }}: {{ $question->subject->sub_title }}</strong>
                </div>
                <div class="card-body">
                    
                    @foreach ($question->subject->questions as $key => $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" 
                                   value="{{ $option->question_section }}" id="option{{ $question->id }}{{ $option->question_title }}" required>
                            <label class="form-check-label" for="option{{ $question->id }}{{ $option->question_title }}">
                                {{ $option->question_title }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success w-100">Submit Exam</button>
    </form>
</div>
@endsection
