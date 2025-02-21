@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Set Exam</h2>

    <div class="card shadow p-4">
        <form action="" method="POST">
            @csrf
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Paper name</th>
                        <th>Total question</th>
                        {{-- <th>Course</th> --}}
                        <th>User created</th>
                        <th>created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="questionTable">
                    @foreach ($question_papers as $question_paper)
                        <tr>
                            <td>{{ $question_paper->paper_name }}</td>
                            <td>{{ $question_paper->total_question }}</td>
                            <td>{{ $question_paper->exam_question->first()->user->name }}</td>
                            <td>{{ $question_paper->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>

<!-- jQuery for Dynamic Question Addition -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#addQuestion").click(function () {
        $("#questionTable").append(`
            <tr>
                <td><input type="text" class="form-control" name="questions[]" required></td>
                <td><input type="text" class="form-control" name="options[][0]" required></td>
                <td><input type="text" class="form-control" name="options[][1]" required></td>
                <td><input type="text" class="form-control" name="options[][2]" required></td>
                <td><input type="text" class="form-control" name="options[][3]" required></td>
                <td>
                    <select class="form-control" name="correct_answers[]">
                        <option value="0">Option 1</option>
                        <option value="1">Option 2</option>
                        <option value="2">Option 3</option>
                        <option value="3">Option 4</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-question">✖</button>
                </td>
            </tr>
        `);
    });

    $(document).on("click", ".remove-question", function () {
        $(this).closest("tr").remove();
    });
});
</script>
@endsection
