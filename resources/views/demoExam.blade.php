@extends('layout')

@section('content')
<div class="container mt-5">
    <div id="timer" style=" z-index:1000; position: fixed; top: 60px; right: 20px; background-color: #f8d7da; padding: 10px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <strong>Time Left:</strong> <span id="time">00:00</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let duration = sessionStorage.getItem('remainingTime') 
            ? parseInt(sessionStorage.getItem('remainingTime')) 
            : {{ $question_paper->time_limit??10 }} * 60;

            const timerElement = document.getElementById('time');

            function updateTimer() {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            sessionStorage.setItem('remainingTime', duration);

            if (duration > 0) {
                duration--;
            } else {
                clearInterval(timerInterval);
                alert('Time is up!');
                sessionStorage.removeItem('remainingTime');
                document.querySelector('form').submit();
            }
            }

            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>
    <h2 class="text-center">Student Exam</h2>

    <form action="{{ route('demoexam.submit')}}" method="POST">
        @csrf
        <input type="hidden" name="paper_id" value="{{ $question_paper->id }}">

        @foreach ($exam_questions as $question)

            <div class="card mb-3">
                <div class="card-header">
                    {{-- <strong>Q{{ $loop->iteration }}: {{ $question->subject->sub_title }}</strong> --}}
                    <span>
                        <strong>Q{{ $loop->iteration }}:</strong>
                    </span>
                    {{-- <textarea class="form-control mt-2" rows="5" disabled>{{ $question->subject->sub_title }}</textarea> --}}
                    @if (strpos($question->subject->sub_title, "\n") !== false)
                        {!! nl2br(e($question->subject->sub_title)) !!}
                    @else
                        {{ $question->subject->sub_title }}
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        @php
                            $content=json_decode($question->subject->sub_content);
                            
                            // if($question->subject->sub_content!=""){
                            //     $content=json_decode($question->subject->sub_content);
                            // }else{
                            //     $content="";
                            // }
                            
                        @endphp
                        @if ($content->type=="picture")
                            <img src="{{ asset($content->content) }}" alt="preview image" style="display:block; max-width: 200px; max-height:150px; margin-top: 10px;" >
                        @endif
                    </div>
                    @foreach ($question->subject->questions as $key => $option)
                        <div class="form-check">
                            
                            <input class="form-check-input" type="radio" name="answers[{{ $question->subject->id }}]" 
                                   value="{{ $option->question_section }}" id="option{{ $question->subject->id }}{{ $option->question_title }}" required>
                            <label class="form-check-label" for="option{{ $question->subject->id }}{{ $option->question_title }}">
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
