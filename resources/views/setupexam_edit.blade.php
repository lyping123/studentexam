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
        
        <h2 class="text-center mb-4">Modify exam question form</h2>
        <div class="row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body">
                        
                        <form action="{{ route('exam.editquestion',$paper->id) }}" method="get">
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
                            <input type="hidden" name="question_paper_id" value="{{ $paper->id }}" />
                            <input type="checkbox" id="checkall" class="form-check-input me-1" >
                            <button type="submit" class="btn btn-success">add to </button>
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
                        <form action="{{ route('exam.update.editquestion',$paper->id) }}" method="post">
                            @csrf
                            @method("PUT")
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="add question paper name" name="paper_name" value="{{ $paper->paper_name }}">
                            
                            <button class="btn btn-outline-success" type="submit">modify</button>
                        </div>
                        </form>
                        <form method="POST" action="{{ route('exam.update.deleteAll',$paper->id) }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Clear all</button>

                        </form>
                        <ul class="list-group mt-2 scrollable-list">
                            @if (count($question_papers)!=0)
                                
                               @foreach ($question_papers as $question_paper)
                               
                                    <form action="{{ route('exam.update.delete',$question_paper->id) }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <input type="hidden" name="search" value="{{ request()->get('search') ?? '' }}">
                                        
                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1 overflow-hidden" style="max-width: 100%;">
                                            <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                                            <span class="scrollable-label" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; max-width: 250px;">{{ $question_paper->subject->sub_title }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger btn-sm ms-2" title="Delete" style="flex-shrink: 0;">
                                            <i class="fa fa-trash"></i>
                                        </button>
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

        
        $("#subjectList").on("click","button",function(){
            $("#subject_title").val($(this).text());
            $("#subjectList").empty();
        });
       

    });
    </script>
@endsection