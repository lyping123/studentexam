@extends('layout')

@section('content')
<div class="container mt-5">
    <div id="timer" style=" z-index:1000; position: fixed; top: 60px; right: 20px; background-color: #f8d7da; padding: 10px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <strong>Time Left:</strong> <span id="time">00:00</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paperId = {{ $question_paper->id }};
            const durationMinutes = {{ (int) $question_paper->time_limit }};

            @php
                $startIso = ($question_paper->start_datetime instanceof \Carbon\Carbon)
                    ? $question_paper->start_datetime->toIso8601String()
                    : \Carbon\Carbon::parse($question_paper->start_datetime)->toIso8601String();
            @endphp

            const startIso = '{{ $startIso }}';               // e.g. 2025-10-27T12:30:00+00:00
            const startMs  = Date.parse(startIso);            // milliseconds
            let endMs = Number(sessionStorage.getItem('exam_end_' + {{ $question_paper->id }}));

            // Persist a stable end time per paper in this tab session
            if (!endMs || Number.isNaN(endMs)) {
                endMs = startMs + durationMinutes * 60 * 1000; // minutes -> ms
                sessionStorage.setItem('exam_end_' + {{ $question_paper->id }}, String(endMs));
            }

            const timerElement = document.getElementById('time');
            let timerInterval = null;

            function render(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                timerElement.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }

            function tick() {
                const now = Date.now();
                // If not started yet, show 00:00 until start (you can change this to a “starts in” countdown)
                const effectiveNow = Math.max(now, startMs);
                const remainingSec = Math.max(0, Math.floor((endMs - effectiveNow) / 1000));

                render(remainingSec);

                if (remainingSec <= 0) {
                    clearInterval(timerInterval);
                    sessionStorage.removeItem('exam_end_' + {{ $question_paper->id }});
                    alert('Time is up!');
                    document.querySelector('form').submit();
                }
            }

            timerInterval = setInterval(tick, 1000);
            tick();
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
