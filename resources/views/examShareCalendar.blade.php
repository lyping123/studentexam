@extends('layout')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Student exam calendar</h2>
    <h3>Month: {{ carbon\carbon::createFromDate(request('year') ? request('year') : now()->format('Y'), request('month') ? request('month') : now()->format('m'), 1)->format('F Y') }}</h3>
    <div id="calendarMode" class="tab-pane fade show">
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
                        @if ($day <= $date->daysInMonth && $date->month == $month && !$date->isSaturday() && !$date->isSunday())
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
                            @if ($day <= $date->daysInMonth && $date->month == $month && !$date->isSaturday() && !$date->isSunday())
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
@endsection