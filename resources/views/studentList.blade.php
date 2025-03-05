@extends("layout")
@section('content')


<div class="container mt-5">
    
    <h2 class="text-center mb-4">Students List</h2>
    
    <div class="card shadow p-4">
        
        <form action="{{ route('student.list') }}" method="get">
            @csrf
            <div class="row">
            
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="question_paper">lecturer Group</label>
                        <select name="name" id="name" class="form-control">
                            <option value="">Select lecturer</option>
                            @foreach ($groupName as $group)
                                <option {{ $selected=(request()->get('name')==$group->id)?"selected":"" }} value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                <div class="col-md-4">
                    
                    <button type="button" id="addstudent" data-toggle="modal" data-target="#addStudent" class="btn btn-success mt-4 pull-right"><i class="fa fa-plus"></i></button>
                </div>
                
            </div>
        </form>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Student name</th>
                    <th>Password</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($groupstudent) == 0)
                    <tr>
                        <td colspan="6" class="text-center">No data found</td>
                    </tr>
                @endif
                @foreach ($groupstudent as $student)
                    <tr>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $student->user->password}}</td>
                        <td>
                            <button class="btn btn-warning">Edit</button>
                        </td>
                        <td>
                            <form action="{{  route('student.delete', $student->id) }} " method="post">
                                @csrf
                                @method('DELETE')
                            <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="modal fade" id="addStudent" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Register Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <form id="addForm" action="{{ route('student.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="name" name="name" />
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Password confirm</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">lecturer Group</label>
                                <select class="form-select" id="status" name="user" required>
                                    <option value="">Select lecturer</option>
                                    @foreach ($groupName as $group)
                                        <option {{ $selected=(request()->get('name')==$group->id)?"selected":"" }} value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveChanges">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#addstudent").on("click",function(){
                $("#addStudent").modal("show");
            });
        });
    </script>
</div>
@endsection