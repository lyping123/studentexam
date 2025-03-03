<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Models\exam_question;
use App\Models\StudentAnswer;
use App\Models\question_paper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class demoExamController extends Controller
{
    public function index($question_paper){
        // dd($question_paper);
        $decryptedId = Crypt::decrypt($question_paper);
        $question_paper=question_paper::find($decryptedId);
        
        $exam_questions = $question_paper->exam_question()->get();
        return view('demoExam',compact("exam_questions","question_paper"));
    }

    public function submitExam(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);

        
        $question_paper=question_paper::find($request->paper_id);

        if($question_paper->limit_submit_per_day){
            $existingAttempt = ExamAttempt::where('student_id', Auth::id())
            ->where('paper_id', $request->paper_id)
            ->whereDate('created_at', Carbon::today())
            ->exists();
            
            if ($existingAttempt) {
                return redirect()->back()->withErrors('You have already submitted this exam today.');
            }
        }
        $examAttenpt=ExamAttempt::create([
            "student_id"=>Auth::id(),
            'paper_id'=>$request->paper_id,
        ]);

        $attenpt_id=$examAttenpt->id;
        foreach ($request->answers as $question_id => $answer) {
            
            StudentAnswer::create([
                'student_id' => Auth::id(), 
                'subject_id' => $question_id,
                'paper_id'=>$request->paper_id,
                'attempt_id'=> $attenpt_id,
                'answer' => $answer
            ]);
        }

        
        return redirect()->route('demoexam.review', $attenpt_id)->with('success', 'Exam submitted successfully!');
    }

    public function examReview(ExamAttempt $ExamAttempt)
    {
        
        $attenpt_id=$ExamAttempt->id;

        $exam_questions=$ExamAttempt->question_paper->exam_question()->get();
        $question_paper=$ExamAttempt->question_paper();
        
        $studentAnswers=StudentAnswer::where("attempt_id",$attenpt_id)->pluck('answer',"subject_id");
       
        return view("examReview",compact("studentAnswers","question_paper","exam_questions"));
    }

    public function examReviewlist()
    {
        $question_papers=question_paper::all();
        $examAttenpts=ExamAttempt::filter(request('question_paper'))->get();
        
        foreach ($examAttenpts as $attempt) {
            $correctCount = StudentAnswer::where('attempt_id', $attempt->id)
                ->whereHas('subject', function ($query) {
                    $query->whereColumn('student_answers.answer', 'subjects.correct_ans');
                })
                ->count();
            $attempt->correct_answers = $correctCount ? $correctCount : 0;
        }
        
        // dd();
        return view("examReviewList",compact("examAttenpts","question_papers"));
    }
}

?>