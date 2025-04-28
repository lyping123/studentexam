@extends('layout')

@section('content')
<style>
    .pagination .page-link {
        font-size: 14px; /* Adjust text size */
        padding: 8px 12px; /* Adjust spacing */
    }

    .pagination .page-item span,
    .pagination .page-item a {
        font-size: 16px; /* Adjust Next/Previous button size */
    }
</style>
<div class="container mt-5">
    <h2 class="text-center mb-4">Upload Exam JSON File</h2>

    <div class="card shadow p-4">
        <form action="{{ route('exam.uploadJson') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="subject_title" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_title" name="subject_title" required>
                <ul class="list-group" id="subjectList">
                   
                </ul>
            </div>

            <div class="mb-3">
                <label for="jsonFile" class="form-label">Upload JSON File</label>
                <input type="file" class="form-control" id="jsonFile" name="jsonFile" accept=".json" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Upload & Submit</button>
        </form>
    </div>


    <div class="row">
        
        <div class="col-md-12 mt-4">
            <h2 class="text-center mb-4">Exam subjects</h2>
            
            <div class="d-flex justify-content-between mb-2">
                <div class="col-md-6">
                    <form action="{{ route('exam.index') }}" method="get">
                        <div class="input-group mb-3">
                            {{-- <input type="text" class="form-control" placeholder="Search by subject title" name="search" value="{{ request()->search }}"> --}}
                            <select name="search" id="" class="form-control">
                                <option value="">Choose subject title</option>
                                @foreach ($subject_titles as $subject_title)
                                    <option {{ $subject_title->id==request()->get('search')? "selected": "" }} value="{{ $subject_title->id }}">{{ $subject_title->subject_name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            <form method="POST" action="{{ route('exam.delete') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
               
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkall" ></th>
                        <th>No</th>
                        <th>Subject question</th>
                        <th>Subject title</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @unless ($subjects->count())
                        <tr>
                            <td colspan="4" class="text-center">No data found</td>
                        </tr>
                    @endunless

                    @foreach ($subjects as $subject)
                        <tr class="main-row" data-toggle="subtable-{{ $subject->id }}">
                            <td><input type="checkbox" name="checkid[]" value="{{ $subject->id }}" id=""></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if (strpos($subject->sub_title, "\n") !== false)
                                    {!! nl2br(e($subject->sub_title)) !!}
                                @else
                                    {{ $subject->sub_title }}
                                @endif
                            </td>
                            <td>{{ $subject->subject_title->subject_name }}</td>
                            <td>
                                <a href="{{ route('exam.editquestionband',$subject->id) }}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                            </td>
                        </tr>
                        <tr id="subtable-{{ $subject->id }}" class="sub-table-row" style="display: none;">
                            
                            <td colspan="2">
                                <div class="mb-2">
                                    <strong>Question Content:</strong>
                                    @php
                                        $content=json_decode($subject->sub_content);
                                    @endphp
                                    @if ($content->type=="picture")
                                        <img src="{{ asset($content->content) }}" alt="preview image" style="display:block; max-width: 200px; max-height:150px; margin-top: 10px;" >
                                    @else
                                        {{ "none content" }}
                                    @endif
                                </div>
                            </div>
                            </td>
                            <td colspan="3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Question Title</th>
                                            <th>Is Correct?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subject->questions as $option)
                                            <tr>
                                                <td>{{ $option->question_title }}</td>
                                                <td>
                                                    @if($subject->correct_ans==$option->question_section)
                                                        ✅ Correct
                                                    @else
                                                        ❌ Incorrect
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </form>
            
            {{-- {{ $subjects->links() }} --}}
            
            
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $("#checkall").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".main-row td:not(:first-child)").click(function () {
            var target = $(this).closest("tr").data("toggle");
            $("#" + target).toggle();
        });

        

        $("div.alert").on("click", function () {
            $(this).remove();
        });
       
    });


</script>
@endsection
