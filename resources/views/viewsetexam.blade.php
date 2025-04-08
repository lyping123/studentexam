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
                        <th>limit submit per day</th>
                        <th>Time limit(minutes)</th>
                        <th>Status</th>
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
                            
                            <td>{{ $question_paper->limit_submit_per_day==1 ? "yes":"no" }}</td>
                            <td>{{ $question_paper->time_limit }}</td>
                            <td>{{ $question_paper->status==1 ? "Active":"Inactive" }}</td>
                            <td>{{ $question_paper->exam_question->first()->user->name }}</td>
                            <td>{{ $question_paper->created_at->format("d-m-Y") }}</td>
                            <td style="display: flex;">
                                <a href="{{ route('demoexam.index', $question_paper->id) }} " target='_blank' class="btn btn-secondary btn-sm"><i class="fa fa-share-square-o" aria-hidden="true"></i>
                                </a>
                                {{-- <a href="{{ route('download.docx', $question_paper->id) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i></a> --}}
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" value="{{ $question_paper->id }}"  data-target="#downloadModal"><i class="fa fa-download" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" value="{{ $question_paper->id }}"  data-target="#editModal"><i class="fa fa-cog" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm sharelink" value="{{ encrypt($question_paper->id) }}"><i class="fa fa-clone" aria-hidden="true"></i></button>
                            </td>
                            <td>
                                <a href="{{ route('exam.editquestion', $question_paper->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td>
                                
                                <form method="POST" action="{{ route('exam.set.delete', $question_paper->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" ><i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <form id="editForm" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="limit_submit" class="form-label">Limit Submit per Day</label>
                                    <div class="mb-3">
                                        <input type="radio" id="limit_1" name="limit_submit_per_day" value="1" required>
                                        <label for="limit_1">Yes</label>
                                        <input type="radio" id="limit_2" name="limit_submit_per_day" value="0" required>
                                        <label for="limit_2">No</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="time_limit" class="form-label">Time Limit (in minutes)</label>
                                    <input type="number" class="form-control" id="time_limit" name="time_limit" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveChanges">Save Changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Convert to word document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <input type="hidden" id="downloadId" value="" />
                        <div class="modal-body">
                            <form id="downloadForm" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="limit_submit" class="form-label">Exam type</label>
                                    <div class="mb-3">
                                        <input type="radio" id="limit_1" checked name="exam_type" value="pretest" >
                                        <label for="limit_1">Pretest</label>
                                        <input type="radio" id="limit_2" name="exam_type" value="examination" >
                                        <label for="limit_2">Examination</label>
                                    </div>
                                </div>
                                <div id="download_content" style="display: none;">
                                    <div class="mb-3">
                                        <label for="code_program" class="form-label">Code program</label>
                                        <input type="text" class="form-control" id="code_program" name="code_program" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="program_name" class="form-label">Program name</label>
                                        <input type="text" class="form-control" id="program_name" name="program_name" />
                                    </div>
                                </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveChanges">Download</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <script>
    $(document).ready(function() {
        $("#questionTable").on("click", ".btn-success", function() {
            var id = $(this).val();
            $.get(`{{ route('exam.setting',':paper_id') }}`.replace(':paper_id',id), function(data) {
                $("#editForm").attr("action", `{{ route('exam.setting.save',':paper_id') }}`.replace(':paper_id',id));
                let paper_data=data.data;
                $("#time_limit").val(paper_data.time_limit);
               
                if(paper_data.limit_submit_per_day == 1) {
                    $("#limit_1").attr("checked", true);
                    $("#limit_2").attr("checked", false);
                }else {
                    $("#limit_2").attr("checked", true);
                    $("#limit_1").attr("checked", false);
                }
                $("#status option").each(function() {
                    $(this).removeAttr("selected");
                });
                if(paper_data.status == 1) {
                    $("#status option[value='1']").attr("selected", "selected");
                }else{
                    $("#status option[value='0']").attr("selected", "selected");
                }
                $("#editModal").modal("show");
            });
        });
        $("#questionTable").on("click",".btn-info",function() {
            var id = $(this).val();
            $("#downloadId").val(id);
            $("#downloadModal").modal("show");
        });

        $("#downloadModal").on("change","input[type='radio']",function(){
            var exam_type = $(this).val();
            var id = $("#downloadId").val();
            if(exam_type=="pretest"){
                $("#downloadForm").attr("action", `{{ route('pretest.docx',':paper_id') }}`.replace(':paper_id',id));
                $("#code_program").attr("required",false);
                $("#program_name").attr("required",false);
                $("#download_content").attr("style","display:none;");
            }else{
                $("#downloadForm").attr("action", `{{ route('examination.docx',':paper_id') }}`.replace(':paper_id',id));
                $("#code_program").attr("required",true);
                $("#program_name").attr("required",true);
                $("#download_content").attr("style","display:block;");
            }
            
        });

        $('#questionTable').on("click",".sharelink",function() {
            var id = $(this).val();

            var link=`{{ route('student.demoexam.index',':paper_id') }}`.replace(':paper_id',id);
            alert("link have been copied to clipboard");
            navigator.clipboard.writeText(link);
            
        });
    });
    
    </script>
</div>

@endsection
