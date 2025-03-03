@extends("layout")
@section('content')


<div class="container mt-5">
    
    <h2 class="text-center mb-4">Student exam result</h2>
    <div class="card shadow p-4">
        <form action="{{ route('demoexam.review.list') }}" method="get">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="question_paper">Question paper</label>
                    <select name="question_paper" id="question_paper" class="form-control">
                        <option value="">Select question paper</option>
                        @foreach ($question_papers as $question_paper)
                            <option value="{{ $question_paper->id }}">{{ $question_paper->paper_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Student name</th>
                    <th>Question paper</th>
                    {{-- <th>Course</th> --}}
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
</div>
@endsection