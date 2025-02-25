<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\question_paper;
use Illuminate\Support\Facades\Auth;

class demoExamController extends Controller
{
    public function index(question_paper $question_paper){
        $exam_questions = $question_paper->exam_question()->get();
        return view('demoExam',compact("exam_questions","question_paper"));
    }

    public function submitExam(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);

        foreach ($request->answers as $question_id => $answer) {
            StudentAnswer::create([
                'student_id' => Auth::id(), 
                'subject_id' => $question_id,
                'paper_id'=>$request->paper_id,
                'answer' => $answer
            ]);
        }

        return redirect()->route('demoexam.index', $request->paper_id)->with('success', 'Exam submitted successfully!');
    }
}

?>