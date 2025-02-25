@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Set Exam</h2>

    <div class="card shadow p-4">
            @csrf
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Paper name</th>
                        <th>Total question</th>
                        {{-- <th>Course</th> --}}
                        <th>User created</th>
                        <th>created Date</th>
                        <th colspan="3">Action</th>
                    </tr>
                </thead>
                <tbody id="questionTable">
                    @foreach ($question_papers as $question_paper)
                        <tr>
                            <td>{{ $question_paper->paper_name }}</td>
                            <td>{{ $question_paper->total_question }}</td>
                            <td>{{ $question_paper->exam_question->first()->user->name }}</td>
                            <td>{{ $question_paper->created_at }}</td>
                            <td>
                                <a href="{{ route('demoexam.index', $question_paper->id) }}" class="btn btn-primary btn-sm">Demo question</a>
                            </td>
                            <td>
                                <a href="{{ route('exam.editquestion', $question_paper->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                            <td>
                                
                                <form method="POST" action="{{ route('exam.set.delete', $question_paper->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" >Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>

@endsection
