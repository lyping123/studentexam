@extends("layout")
@section('content')


<div class="container mt-5">
    
    <h2 class="text-center mb-4">Student exam result</h2>
    <div class="card shadow p-4">
        <form action="{{ route('demoexam.review.list') }}" method="get">
        @csrf
        <fieldset class="border p-4">
            <legend class="w-auto px-2">Search Exam Results</legend>
            <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Student name</label>
                    <input type="text" name="student" class="form-control" placeholder="Enter student name" value="{{ request('student') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="question_paper">Question paper</label>
                <select name="question_paper" id="question_paper" class="form-control">
                    <option value="">Select question paper</option>
                    @foreach ($question_papers as $question_paper)
                    <option {{ $question_paper->id==request()->get('question_paper')?'selected':'' }} value="{{ $question_paper->id }}">{{ $question_paper->paper_name }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="course">Month</label>
                <select name="month" id="month" class="form-control">
                    <option value="">Select month</option>
                    <option {{ request('month')=='01'?'selected':'' }} value="01">January</option>
                    <option {{ request('month')=='02'?'selected':'' }} value="02">February</option>
                    <option {{ request('month')=='03'?'selected':'' }} value="03">March</option>
                    <option {{ request('month')=='04'?'selected':'' }} value="04">April</option>
                    <option {{ request('month')=='05'?'selected':'' }} value="05">May</option>
                    <option {{ request('month')=='06'?'selected':'' }} value="06">June</option>
                    <option {{ request('month')=='07'?'selected':'' }} value="07">July</option>
                    <option {{ request('month')=='08'?'selected':'' }} value="08">August</option>
                    <option {{ request('month')=='09'?'selected':'' }} value="09">September</option>
                    <option {{ request('month')=='10'?'selected':'' }} value="10">October</option>
                    <option {{ request('month')=='11'?'selected':'' }} value="11">November</option>
                    <option {{ request('month')=='12'?'selected':'' }} value="12">December</option>
                </select>
                </div>
            </div>
            
            
            <div class="col-md-12">
                <div class="form-group">
                <button type="submit" class="btn btn-primary mt-4">Search</button>
                </div>
            </div>
            </div>
        </fieldset>
        </form>

        <div class="col-md-12 mt-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="tableModeTab" href="#tableMode" data-toggle="tab">Table Mode</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="calendarModeTab" href="#calendarMode" data-toggle="tab">Calendar Mode</a>
                </li>
            </ul>
        </div>

        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="tableMode">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Student name</th>
                            <th>Question paper</th>
                            
                            <th>Total correct/question</th>
                            <th>Total mark</th>
                            <th>Answer Date</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody id="questionTable">
                        @if(count($examAttenpts) == 0)
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                        @endif
                        @foreach ($examAttenpts as $examAttenpt)
                            <tr>
                                <td>{{ $examAttenpt->user->name }}</td>
                                <td>{{ $examAttenpt->question_paper->paper_name }}</td>
                                <td>{{ $examAttenpt->correct_answers."/".$examAttenpt->student_answer->count() }}</td>
                                <td>{{ round(($examAttenpt->correct_answers/max(1,$examAttenpt->student_answer->count()))*100,2) }}</td>
                                <td>{{ $examAttenpt->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('demoexam.review', $examAttenpt->id) }}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            <div id="calendarMode" class="tab-pane fade show">
                <button class="btn btn-primary mb-3 pull-right" id="sharecalendar" >share</button>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Student Name</th>
                            @for ($day = 1; $day <= 31; $day++)
                                @php
                                    $month = request('month') ? request('month') : now()->format('m');
                                    $year = now()->format('Y');
                                    $date = \Carbon\Carbon::createFromDate($year, $month, $day);
                                @endphp
                                @if (!$date->isSaturday() && !$date->isSunday())
                                    <th>{{ $day }}</th>
                                @endif
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($examAttenpts) == 0)
                            <tr>
                                <td colspan="32" class="text-center">No data found</td>
                            </tr>
                        @endif
                        @foreach ($examAttenpts as $examAttenpt)
                            <tr>
                                <td>{{ $examAttenpt->user->name }}</td>
                                @for ($day = 1; $day <= 31; $day++)
                                    @php
                                        $date = \Carbon\Carbon::createFromDate($year, $month, $day);
                                    @endphp
                                    @if (!$date->isSaturday() && !$date->isSunday())
                                        @php
                                            $score = $examAttenpts->where('student_id', $examAttenpt->student_id)
                                                ->where('created_at', '>=', $date->startOfDay())
                                                ->where('created_at', '<', $date->endOfDay())
                                                ->first();
                                        @endphp
                                        <td>{{ $score ? round(($score->correct_answers / max(1, $score->student_answer->count())) * 100,0)."%" : '-' }}</td>
                                    @endif
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#calendarModeTab').on('click', function() {
                $(this).tab('show');
                $('#calendarMode').show();
                $('#tableMode').hide();
            });

            $('#tableModeTab').on('click', function() {
                $(this).tab('show');
                $('#calendarMode').hide();
                $('#tableMode').show();
            });
            $('#sharecalendar').on('click', function() {
                var url = "{{ route('exam.share.calendar') }}";
                var month = "{{ request('month') }}";
                var student = "{{ request('student') }}";
                var question_paper = "{{ request('question_paper') }}";
                window.open(url + '?month=' + month + '&student=' + student + '&question_paper=' + question_paper, '_blank');
                
            });
        });
    </script>
</div>
@endsection