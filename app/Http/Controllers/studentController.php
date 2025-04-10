<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\student;

use App\Models\ExamAttempt;
use App\Models\question_paper;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class studentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $examAttenpts=ExamAttempt::where("student_id",Auth::id())->get();

        foreach ($examAttenpts as $attempt) {
            $correctCount = StudentAnswer::where('attempt_id', $attempt->id)
                ->whereHas('subject', function ($query) {
                    $query->whereColumn('student_answers.answer', 'subjects.correct_ans');
                })
                ->count();
            $attempt->correct_answers = $correctCount ? $correctCount : 0;
        }

        
        $upcomingExams=question_paper::where("status",true)->whereHas("exam_question",function($query){
            $studentGroup=student::where("student_id",Auth::id())->first();
            $query->where("user_id",$studentGroup->user_id);  
        })->latest()->take(5)->get();
        return view('student_dashboard',compact("examAttenpts","upcomingExams"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // $role=$request->role=="STAFF"?"admin":"student";
        // return $role;
        // return $request->all();

        // $user=User::create([
        //     "name" => $request->name,
        //     "password" => $request->password,
        //     "role"=>$role,
        // ]);
        
        
        $students=$request->studentNames;
        
        $group=User::where("name",$request->groups)->first();

        
        foreach($students as $student){
            $id=User::where("name",$student)->first()->id;
            
            student::create([
                "user_id" => $group->id,
                "student_id" => $id,
            ]);
        }

        // return response()->json($user, 201);
        // $student=student::create([
        //     "user_id" => $user->id,
        //     "course_id" => $request->course_id,
        //     "gender"=>$request->gender,
        // ]);
        return response()->json($group, 201);

    }

    public function studentListPage(Request $request)
    {
        $search=$request->input("name");
        $groupstudent=student::filter($search)->get();
        
        foreach($groupstudent as $group){
            $students=User::find($group->student_id);
            $group->user=$students;
        }
        
        // dd($groupstudent);
        // $students=User::where("role","student")->get();        
        $groupName=User::where("role","admin")->get();


        return view("studentList",compact("groupstudent","groupName"));
    }

    public function student_register(Request $request)
    {
        $formValidated=$request->validate([
            "name"=>"required|unique:users,name",
            "password"=>"required|confirmed"
        ]);
        
        $user=User::create($formValidated);
        $studentGroup=student::create([
            "user_id"=>$request->user,
            "student_id"=>$user->id
        ]);
        if($studentGroup){
            return redirect()->route("student.list")->with("success","Student register success");
        }
        return redirect()->route("student.list")->withErrors("Register fail please check the data propery");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::find($id);
        $user->delete();
        return redirect()->route("student.list")->with("success","Student deleted successfully");
    }

    public function student_update(Request $request,$id)
    {
        $student=User::find($id);
        $request->validate([
            'name' => 'required',
            'password' => 'required|confirmed'
        ]);
        $student->name = $request->name;
        $student->password= $request->password;
        $student->save();
        return redirect()->route('student.list')->with('success', 'Student updated successfully.');

    }
}
