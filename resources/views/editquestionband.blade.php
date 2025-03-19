@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Modify Question Band Form</h2>

    <div class="card shadow p-4">
        <form action="{{ route('exam.editquestion.edit',$subject->id) }}" method="POST">
            @method("PUT")
            @csrf
            
            <div class="mb-3">
                <label for="subject" class="form-label">Select Subject</label>
                <input type="text" class="form-control" id="subject_title" name="subject_title"  required value="{{ $subject->subject_title->subject_name }}">
                <ul class="list-group" id="subjectList">
                       
                </ul>
            </div>
            <div class="mb-3">
                <label for="exam_title" class="form-label">Subject Title</label>
                <textarea class="form-control" name="sub_title" id="" cols="30" rows="5">{{ $subject->sub_title }}</textarea>
            </div>

            <!-- Subject Selection -->
            

            <!-- Questions Table -->
            <div class="mt-4">
                <div id="questionList">
                    <div class="question-item border p-3 mb-3">
                        <h5>Question</h5>
                        
                        @foreach ($subject->questions as $index=>$option)
                            <div class="mb-2">
                                <label>{{ $option->question_section }}</label>
                                <input type="text" data-option="{{ $option->question_section }}" class="form-control" name="options[]" value="{{ $option->question_title }}"  required>
                            </div>
                        @endforeach
                        <div class="mb-2">
                            <label>Correct Answer</label>
                            <select class="form-control" name="correct_ans">

                                @foreach ($subject->questions as $index => $option)
        
                                    <option value="{{ $option->question_section }}" 
                                        {{ $subject->correct_ans == $option->question_section ? 'selected' : '' }}>
                                        {{ $option->question_section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3 w-100">Save question</button>
        </form>
    </div>
</div>

<!-- jQuery for Dynamic Question Addition -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    $(document).on("click", ".remove-question", function () {
        $(this).closest("tr").remove();
    });
});
</script>
@endsection
