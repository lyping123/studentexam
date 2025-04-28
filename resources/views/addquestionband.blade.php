@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Add Question Band Form</h2>

    <div class="card shadow p-4">
        <form action="{{ route('exam.addquestion.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="subject_title" class="form-label">Subject Type</label>
                <input type="text" class="form-control" id="subject_title" name="subject_title" required >
                <ul class="list-group" id="subjectList">
                       
                </ul>
            </div>
            <div class="mb-3">
                <label for="question_type" class="form-label">Choose Question Type</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" checked type="radio" name="question_type" id="subject_question" value="subject" required>
                    <label class="form-check-label" for="subject_question">
                        Subject Question
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="question_type" id="picture_question" value="picture" required>
                    <label class="form-check-label" for="picture_question">
                        Picture Question
                    </label>
                </div>
                {{-- <div class="form-check form-check-inline">
                    <input class="form-check-input"  type="radio" name="question_type" id="picture_question" value="multitple" required>
                    <label class="form-check-label" for="picture_question">
                        Picture Question
                    </label>
                </div> --}}
                
            </div>
            
            <div class="mb-3">
                <label for="exam_title" class="form-label">Subject Title</label>
                <div id="question-type">

                    <textarea class="form-control" name="sub_title" id="" cols="30" rows="5"></textarea>
                </div>
                
            </div>

            <!-- Subject Selection -->
            

            <!-- Questions Table -->
            <div class="mt-4">
                <div id="questionList">
                    <div class="question-item border p-3 mb-3">
                        <h5>Question</h5>
                        <div class="mb-2">
                            <label>A</label>
                            <input type="text" data-option="A" class="form-control" name="options[]" required>
                        </div>
                        <div class="mb-2">
                            <label>B</label>
                            <input type="text" data-option="B"  class="form-control" name="options[]" required>
                        </div>
                        <div class="mb-2">
                            <label>C</label>
                            <input type="text" data-option="C"  class="form-control" name="options[]" required>
                        </div>
                        <div class="mb-2">
                            <label>D</label>
                            <input type="text" data-option="D"  class="form-control" name="options[]" required>
                        </div>
                        <div class="mb-2">
                            <label>Correct Answer</label>
                            <select class="form-control" name="correct_ans">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
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
    $("#questionList input[name='options[]']").on("focus",function(){
        let data=$(this).data();
        $(this).val(data.option+". ")

    });

    $(document).on("click", ".remove-question", function () {
        $(this).closest("tr").remove();
    });

    $(document).on("change",".form-check-input",function(){
        let value=$(this).val();
        
        let questionType=$("#question-type");
        // console.log(questionType.html());
        if(value=="subject"){
            questionType.html(`<textarea class="form-control" name="sub_title" id="" cols="30" rows="5"></textarea>`);
        }else if(value=="picture"){
            questionType.html(`
            <input type="file" class="form-control" name="sub_image" id="sub_image" required /> 
            <br>
            <img id="preview_image" src="" alt="Preview" style="display:none; max-width: 200px; margin-top: 10px;">
            <br> 
            <br>
            <textarea class="form-control" name="sub_title" id="" cols="30" rows="5" placeholder='subject title' ></textarea>
            `);
        }
    });

    $(document).on("change","#sub_image",function(){
        const file=this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#preview_image')
                    .attr('src', e.target.result)
                    .show(); // show if hidden
            };
            reader.readAsDataURL(file);
        }
    });
    
});
</script>
@endsection
