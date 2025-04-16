@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Modify Question Band Form</h2>

    <div class="card shadow p-4">
        <form action="{{ route('exam.editquestion.edit',$subject->id) }}" method="POST" enctype="multipart/form-data">
            @method("PUT")
            @csrf
            
            <div class="mb-3">
                <label for="subject" class="form-label">Select Subject</label>
                <input type="text" class="form-control" id="subject_title" name="subject_title"  required value="{{ $subject->subject_title->subject_name }}">
                <ul class="list-group" id="subjectList">
                       
                </ul>
            </div>

                @php
                    $question_type = $subject->sub_content ? json_decode($subject->sub_content) : null;
                    $selectedType = $question_type->type ?? 'subject';
                    $image=$question_type->content ?? '';

                    // dd($selectedType);
                @endphp
                
                <div class="mb-3">
                    <label for="question_type" class="form-label">Choose Question Type</label><br>
                
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" 
                            type="radio" 
                            name="question_type" 
                            id="subject_question" 
                            value="subject" 
                            {{ $selectedType === 'subject' ? 'checked' : '' }} 
                            required>
                        <label class="form-check-label" for="subject_question">Subject Question</label>
                    </div>
                
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" 
                            type="radio" 
                            name="question_type" 
                            id="picture_question"
                            data-img="{{ $image }}"
                            data-title={{ $subject->sub_title }}
                            value="picture" 
                            {{ $selectedType === 'picture' ? 'checked' : '' }} 
                            required>
                        <label class="form-check-label" for="picture_question">Picture Question</label>
                    </div>
                </div>
        
                <div class="mb-3">
                    <label for="exam_title" class="form-label">Subject Title</label>
                    <div id="question-type">
                        @if ($selectedType === 'subject')
                            <textarea class="form-control" name="sub_title" id="" cols="30" rows="5">{{ $subject->sub_title }}</textarea>
                        @else
                        <input type="file" class="form-control" name="sub_image" id="sub_image" required /> 
                        <br>
                        <img id="preview_image" src="{{ asset($image) }}" alt="Preview" style="display:block; max-width: 200px; margin-top: 10px;">
                        <br> 
                        <textarea class="form-control" name="sub_title" id="" cols="30" rows="5" placeholder='subject title' >{{ $subject->sub_title }}</textarea>
                        @endif
                        
                    </div>
                    
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
    $(document).on("change",".form-check-input",function(){
        // alert(123);
        let value=$(this).val();
        
        let title =$(this).attr("data-title");
        let img=$(this).attr("data-img");
        console.log(img);

        let questionType=$("#question-type");
        // console.log(questionType.html());
        if(value=="subject"){
            questionType.html(`<textarea class="form-control" name="sub_title" id="" cols="30" rows="5"></textarea>`);
        }else if(value=="picture"){
            questionType.html(`
            <input type="file" class="form-control" name="sub_image" id="sub_image" required /> 
            <br>
            <img id="preview_image" src="${img}" alt="Preview" style="display:block; max-width: 200px; margin-top: 10px;">
            <br> 
            <textarea class="form-control" name="sub_title" id="" cols="30" rows="5" placeholder='subject title' >${title}</textarea>
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
