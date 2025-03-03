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

        $upcomingExams=question_paper::where("status",true)->latest()->take(5)->get();
        return view('student_dashboard',compact("examAttenpts","upcomingExams"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $user=User::create([
            "name" => $request->name,
            "ic" => $request->ic,
            "password" => $request->password,
            "role"=>"student",
            "course_id" => $request->course_id,
        ]);
        // return response()->json($user, 201);
        // $student=student::create([
        //     "user_id" => $user->id,
        //     "course_id" => $request->course_id,
        //     "gender"=>$request->gender,
        // ]);
        return response()->json($user, 201);

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
        //
    }
}
