@extends('layout')
@section('content')
<style>
    .scrollable-list {
        max-height: 700px; /* Adjust height as needed */
        overflow-y: auto; /* Enable vertical scroll */
    }
    .scrollable-label {
        white-space: nowrap;     /* Prevent text from wrapping */
        overflow: hidden;        /* Hide overflow */
        text-overflow: ellipsis; /* Add "..." to cut-off text */
        max-width: 90%;        /* Adjust width as needed */
    }
</style>
    <div class="container mt-5">
        
        <h2 class="text-center mb-4">Set up exam question form</h2>
        <div class="row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('exam.stuquestiton') }}" method="get">
                        <div class="input-group mb-3">
                            {{-- <input type="text" class="form-control" placeholder="Search by subject title" name="search" value="{{ request()->search }}"> --}}
                            <select name="search" id="" class="form-control">
                                <option value="">Choose subject title</option>
                                @foreach ($subject_titles as $subject_title)
                                    <option {{ $selected=request()->get("search")==$subject_title->id? "selected":"" }} value="{{ $subject_title->id }}">{{ $subject_title->subject_name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                        </form>
                        <form action="{{ route('exam.setquestion') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between">
                            <input type="checkbox" id="checkall" class="form-check-input me-1" >
                            <button type="submit" class="btn btn-success">Import</button>
                        </div>
                        <ul class="list-group mt-2 scrollable-list">
                            @if (count($subjects)!=0)
                                
                                @foreach ($subjects as $subject)
                                    <li class="list-group-item ">
                                        <input class="form-check-input me-1" type="checkbox" name="checkid[]" value="{{ $subject->id }}" id="Checkbox{{ $subject->id }}">
                                        <label class="form-check-label scrollable-label" for="Checkbox{{ $subject->id }}">{{ $subject->sub_title }}</label>
                                    </li>
                                @endforeach
                                
                            @else
                            <li class="list-group">
                                <p>None question found</p>
                            </li>
                            @endif
                            
                          </ul>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('exam.update.setquestion') }}" method="post">
                            @csrf
                            @method("PUT")
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="add question paper name" name="paper_name" value="">
                            
                            <button class="btn btn-outline-success" type="submit">Submit</button>
                        </div>
                        </form>
                        <form method="POST" action="{{ route('exam.setup.deleteAll') }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Clear all</button>

                        </form>
                        <ul class="list-group mt-2 scrollable-list">
                            @if (count($question_papers)!=0)
                                
                                @foreach ($question_papers as $question_paper)
                               
                                    <form action="{{ route('exam.setup.delete',$question_paper->id) }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    <li class="list-group-item ">
                                        <button type="submit" class="btn btn-danger btn-sm" >
                                            <i class="fa fa-times"></i> <!-- Font Awesome "X" icon -->
                                        </button>
                                       
                                        <label class="form-check-label scrollable-label" >{{  $question_paper->subject->sub_title }}</label>
                                    </li>
                                    </form>
                                @endforeach
                                
                            @else
                            <li class="list-group">
                                <p>None setup yet</p>
                            </li>
                            @endif
                           
                          </ul>
                    </div>
                </div>
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
        $("#subjectList").on("click","button",function(){
            $("#subject_title").val($(this).text());
            $("#subjectList").empty();
        });
        $("#")

    });
    </script>
@endsection