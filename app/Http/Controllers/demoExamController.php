<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Models\exam_question;
use App\Models\StudentAnswer;
use App\Models\question_paper;
use App\Models\student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class demoExamController extends Controller
{
    public function index($question_paper){
        // dd($question_paper);
        
        $question_paper=question_paper::find($question_paper);
        if($question_paper->random_status==1){
            $exam_questions = $question_paper->exam_question()->inRandomOrder()->limit(60)->get();
        }else{
            $exam_questions = $question_paper->exam_question()->get();
        }
        
        
        return view('demoExam',compact("exam_questions","question_paper"));
    }

    public function indexStudent($question_paper){
        // dd($question_paper);
        
        $decryptedId = Crypt::decrypt($question_paper);
        $question_paper=question_paper::find($decryptedId);
        if($question_paper->random_status==1){
            $exam_questions = $question_paper->exam_question()->inRandomOrder()->limit(60)->get();
        }else{
            $exam_questions = $question_paper->exam_question()->get();
        }
        
        // $exam_questions = $question_paper->exam_question()->get();
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
            ->exists();
            
            if ($existingAttempt) {
                return redirect()->back()->withErrors('You have already submitted this exam.');
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
        // dd($exam_questions=$ExamAttempt->question_paper);
        $question_paper=$ExamAttempt->question_paper()->get();
        // dd($question_paper);
        $studentAnswers=StudentAnswer::where("attempt_id",$attenpt_id)->pluck('answer',"subject_id");
        $correctAnswersCount=StudentAnswer::where('attempt_id', $attenpt_id)
            ->whereHas('subject', function ($query) {
                $query->whereColumn('student_answers.answer', 'subjects.correct_ans')->withoutGlobalScopes();
            })
            ->count();
        
        return view("examReview",compact("studentAnswers","question_paper","exam_questions","correctAnswersCount"));
    }

    public function examReviewlist()
    {
        $question_papers=question_paper::all();
        $studentGroup = student::all();
        
        $filters = request()->only(['student', 'question_paper', 'month']);
        $examAttenpts = ExamAttempt::filter($filters)
            ->whereIn("student_id", $studentGroup->pluck('student_id'))
            ->paginate(10)->appends($filters);
        
        $s_name=request()->only('student');
        
        if(count($s_name)>0){
            $calendar_modes=student::where("user_id",Auth::id())->whereHas("user",function($query) use ($s_name){
                $query->where("name","like","%{$s_name['student']}%");
            })->get();
        }else{
            $calendar_modes=$studentGroup;
        }
        
        // foreach($calendar_modes as $calendar_mode){
        //    $calendar_mode->username=$calendar_mode->user->name;
        //    $student_attempt=$calendar_mode->user->exam_attempt->where("paper_id",7)->first();
        //    if ($student_attempt) {
        //        $correctCount=StudentAnswer::where('attempt_id', $student_attempt->id)
        //             ->whereHas('subject', function ($query) {
        //                 $query->whereColumn('student_answers.answer', 'subjects.correct_ans')->withoutGlobalScopes();
        //             })
        //             ->count();
        //        $calendar_mode->correct_answers = $correctCount ? $correctCount : 0; 
        //    } else {
        //        $calendar_mode->correct_answers = 0;
        //    }
        // }
        
        // dd($examAttenpts);
        foreach ($examAttenpts as $attempt) {
            $correctCount = StudentAnswer::where('attempt_id', $attempt->id)
                ->whereHas('subject', function ($query) {
                    $query->whereColumn('student_answers.answer', 'subjects.correct_ans')->withoutGlobalScopes();
                })
                ->count();
            $attempt->correct_answers = $correctCount ? $correctCount : 0;
        }
        
        return view("examReviewList",compact("examAttenpts","question_papers","calendar_modes"));
    }
    public function demoexamShareCalendar()
    {
        $question_papers=question_paper::all();
        $examAttenpts = ExamAttempt::filter(request()->only(['student', 'question_paper', 'month']))->get();

        $s_name=request()->only('student');
        
        
        foreach ($examAttenpts as $attempt) {
            $correctCount = StudentAnswer::where('attempt_id', $attempt->id)
                ->whereHas('subject', function ($query) {
                    $query->whereColumn('student_answers.answer', 'subjects.correct_ans')->withoutGlobalScopes();
                })
                ->count();
            $attempt->correct_answers = $correctCount ? $correctCount : 0;
        }
        
        return view("examShareCalendar",compact("examAttenpts","question_papers"));
    }
}

?>