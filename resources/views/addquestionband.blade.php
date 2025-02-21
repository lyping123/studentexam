@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Set Exam</h2>

    <div class="card shadow p-4">
        <form action="" method="POST">
            @csrf

            <!-- Exam Title -->
            <div class="mb-3">
                <label for="exam_title" class="form-label">Exam Title</label>
                <input type="text" class="form-control" id="exam_title" name="exam_title" required>
            </div>

            <!-- Subject Selection -->
            <div class="mb-3">
                <label for="subject" class="form-label">Select Subject</label>
                <select class="form-control" id="subject" name="subject_id" required>
                    <option value="">Choose a subject</option>
                   
                </select>
            </div>

            <!-- Questions Table -->
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Question</th>
                        <th>Option 1</th>
                        <th>Option 2</th>
                        <th>Option 3</th>
                        <th>Option 4</th>
                        <th>Correct Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="questionTable">
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
                </tbody>
            </table>

            <!-- Add Question Button -->
            <button type="button" class="btn btn-primary mt-3" id="addQuestion">+ Add Question</button>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3 w-100">Save Exam</button>
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
