@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="mb-3">Recent Exam History</h4>

        @if(isset($examAttenpts) && $examAttenpts->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Questions Paper</th>
                            <th>Attempted On</th>
                            <th>Correct</th>
                            <th>Total</th>
                            <th>Score (%)</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($examAttenpts as $attempt)
                            @php
                                $total = optional($attempt->student_answer)->count() ?? 0;
                                $correct = $attempt->correct_answers ?? 0;
                                $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attempt->question_paper->paper_name }}</td>
                                <td>{{ $attempt->created_at?->format('d-m-Y H:i') }}</td>
                                <td>{{ $correct }}</td>
                                <td>{{ $total }}</td>
                                <td>{{ $score }}</td>
                                <td>
                                    @if ($score >= 60)
                                        <span class="badge bg-success">Passed</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (method_exists($examAttenpts, 'links'))
                <div class="d-flex justify-content-center">
                    {{ $examAttenpts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="text-center text-muted py-5">No recent exams found.</div>
        @endif
    </div>
</div>
@endsection