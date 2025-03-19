<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\subject;
use App\Models\userLog;
use App\Models\question;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Models\exam_question;
use App\Models\StudentAnswer;
use App\Models\subject_title;
use App\Models\question_paper;
use Illuminate\Support\Facades\Auth;

class examController extends Controller
{

    public function addquestionPage()
    {
        $subject_titles=subject_title::all();
        return view("addquestionband",compact("subject_titles"));    
    }

    public function addquestionSubmit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            "subject_title"=>"required",
            "sub_title"=>"required|unique:subjects,sub_title",
            "options"=>"required",
            "correct_ans"=>"required"
        ]);
        $subject_title=subject_title::where('subject_name',$request->subject_title)->first();
        if(!$subject_title){
            $subject_title=subject_title::create([
                'subject_name'=>$request->subject_title
            ]);
            
        }
        $subbtitlei_id=$subject_title->id;
        
        $subject=subject::create([
            "user_id"=>Auth::id(),
            "sub_title"=>$request->sub_title,
            "correct_ans"=>$request->correct_ans,
            "subject_id"=>$subbtitlei_id
        ]);
        if($subject){
            // dd($request->options);
            $options=$request->options;
            
            $array=array("A","B","C","D");
            foreach($options as $index=>$option){
                question::create([
                    "question_section"=>$array[$index],
                    "question_title"=>$option,
                    "subject_id"=>$subject->id,
                ]);
            }
            return back()->with("success","Question band added success");
        }
        return back()->withErrors("Question bank added fail please try again");
        
    }
    public function editquestionPage($id)
    {
        $subject_titles=subject_title::all();
        $subject=subject::find($id);
        return view("editquestionband",compact("subject","subject_titles"));
    }

    public function editquestionband(Request $request,subject $subject)
    {
        $request->validate([
            "subject_title"=>"required",
            "sub_title"=>"required|unique:subjects,sub_title,".$subject->id,
            "options"=>"required",
            "correct_ans"=>"required"
        ]);
        $subject_title=subject_title::where('subject_name',$request->subject_title)->first();
        if(!$subject_title){
            $subject_title=subject_title::create([
                'subject_name'=>$request->subject_title
            ]);
            
        }
        $subbtitlei_id=$subject_title->id;
        $subject->update([
            "sub_title"=>$request->sub_title,
            "correct_ans"=>$request->correct_ans,
            "subject_id"=>$subbtitlei_id,
        ]);
        $subject->questions()->delete();
        $options=$request->options;
        $array=array("A","B","C","D");
        foreach($options as $index=>$option){
            question::create([
                "question_section"=>$array[$index],
                "question_title"=>$option,
                "subject_id"=>$subject->id,
            ]);
        }
        return redirect()->route("exam.index")->with("success","question paper edited success");


    }
    public function dashboard()
    {
        $recentStudents = User::where('role', 'student')->latest()->take(5)->get();
        $total_student=User::where('role','student')->count();
        $totalPapers=question_paper::count();
        $examAttenpts=ExamAttempt::where('created_at', '>=', now()->subDay())->get();
        
        foreach ($examAttenpts as $attempt) {
            $correctCount = StudentAnswer::where('attempt_id', $attempt->id)
                ->whereHas('subject', function ($query) {
                    $query->whereColumn('student_answers.answer', 'subjects.correct_ans');
                })
                ->count();
            $attempt->correct_answers = $correctCount ? $correctCount : 0;
            $attempt->total_mark=round(($attempt->correct_answers/max(1,$attempt->student_answer->count()))*100,2);
        }
       
        $passed=$examAttenpts->where('total_mark','>=',60)->count();
        $failed=$examAttenpts->where('total_mark','<',60)->count();



        return view('admin_dashboard',compact("recentStudents","total_student","totalPapers","passed","failed"));
    }
    public function index(Request $request)
    {
        $subject_titles=subject_title::all();
        $search=$request->input("search");
        $subjects=subject::filter($search)->latest()->get();
        // dd($subjects->subject_title);
        // $subject_title=subject::find(1);
        // dd($subject_title->subject_title);
        return view('index',compact('subjects','subject_titles'));
    }

    public function setquestionPage(Request $request)
    {
        $subject_titles=subject_title::all();
        $question_papers=exam_question::where("status",false)->get();
        $search=$request->input("search");
        $subjects=subject::filter($search)->get();
        return view('setupexam',compact('subjects','subject_titles','question_papers'));
    }

    public function saveQuestionPaperSetting(Request $request,$id){
        $formvalidated=$request->validate([
            "limit_submit_per_day"=>"required|boolean",
            "time_limit"=>"required|numeric",
            "status"=>"required|boolean"
        ]);
        $question_paper=question_paper::find($id);
        $question_paper->update($formvalidated);
        return redirect()->route("exam.viewsetquestion")->with("success","exam setting updated success");
       
    }
    public function updatequestionPage(Request $request,question_paper $question_paper)
    {
        $subject_titles=subject_title::all();
        $question_papers=$question_paper->exam_question()->get();
        $paper=$question_paper;
        
        $search=$request->input("search");
        $subjects=subject::filter($search)->get();
        return view('setupexam_edit',compact('subjects','subject_titles','question_papers','paper'));
    }

    public function updatequestion(Request $request,question_paper $question_paper)
    {
        $request->validate([
            "paper_name"=>"required|unique:question_papers,paper_name"
        ]);

        $exam_question=$question_paper->exam_question();
        $question_paper->update([
            "paper_name"=>$request->paper_name,
            "total_question"=>count($exam_question->get()),
        ]);

        $exam_question->update([
            "status"=>true,
        ]);

        if($exam_question){
            return redirect()->route("exam.viewsetquestion")->with("success","set question modify success");
        }

        return redirect()->route("exam.editquestion",$question_paper->id)->withErrors("set question modify fail");
    }

    public function setquestion(Request $request)
    {
        if($request->input("question_paper_id")){
            $subjects=$request->input("checkid");
            foreach($subjects as $subject){
                $examquestions=new exam_question();
                $examquestions->paper_id=$request->input("question_paper_id");
                $examquestions->subject_id=$subject;
                $examquestions->user_id=Auth::id();
                $examquestions->status=true;
                $examquestions->save();
            }
            return redirect()->route("exam.editquestion",$request->input("question_paper_id"))->with("success","Question addded success");
        }   
        $subjects=$request->input("checkid");
        foreach($subjects as $subject){
            $examquestions=new exam_question();
            $examquestions->subject_id=$subject;
            $examquestions->user_id=Auth::id();
            $examquestions->status=false;
            $examquestions->save();
        }

        return redirect()->route("exam.stuquestiton")->with("success","Question addded success");
    }



    public function updatesetquestion(Request $request){
        $request->validate([
            "paper_name"=>"required|unique:question_papers,paper_name"
        ]);

        $exam_question=exam_question::where("status",false);

        $question_paper=question_paper::create([
            "paper_name"=>$request->paper_name,
            "total_question"=>count($exam_question->get()),
        ]);

        $insertid=$question_paper->id;
        $exam_question->update([
            "paper_id"=>$insertid,
            "status"=>true,
        ]);
        if($exam_question){
            return redirect()->route("exam.stuquestiton")->with("success","set question added success");
        }

        return redirect()->route("exam.stuquestiton")->withErrors("set question added fail");

    }
    public function deletesetupAll()
    {
        $examquestions=exam_question::where("user_id",Auth::id())->where("status",false);
        $examquestions->delete();
        return redirect()->route("exam.stuquestiton")->with("success","exam question deleted success");
    }

    public function deleteupdateAll(question_paper $question_paper)
    {
        $question_paper->exam_question->delete();
        return redirect()->route("exam.editquestion",$question_paper->id)->with("success","exam question deleted success");
    }
    public function deletesetup(exam_question $exam_question)
    {
        $exam_question->delete();
        return redirect()->route("exam.stuquestiton")->with("success","exam question deleted success");
    }

    public function deleteupdate(exam_question $exam_question,question_paper $question_paper)
    {
        $exam_question->delete();
        return redirect()->route("exam.editquestion",$question_paper->id)->with("success","exam question deleted success");
    }

    public function deletesetexam(question_paper $question_paper){
        $question_paper->delete();
        return redirect()->route("exam.viewsetquestion")->with("success","question paper deleted success");
    }

    public function viewsetquestionPage()
    {
        $question_papers=question_paper::all();
        
        return view("viewsetexam",compact("question_papers"));
    }

    

    public function uploadJson(Request $request)
    {
        $request->validate([
            "subject_title"=>'required',
            'jsonFile' => 'required|mimes:json'

        ]);
        $subject_title=subject_title::where('subject_name',$request->subject_title)->first();
        if(!$subject_title){
            $subject_title=subject_title::create([
                'subject_name'=>$request->subject_title
            ]);
            
        }
        $subbtitlei_id=$subject_title->id;

        
        if($request->hasFile('jsonFile')){
            $jsonFile = $request->file('jsonFile');
            $jsonData = file_get_contents($jsonFile->getPathname());

            $exams=json_decode($jsonData,true);
            if(!$exams){
                return redirect()->route('exam.index')->withErrors('Invalid Json File');
            }
            
            $alphakey=range('A','Z');
            $subjectids=array();
            foreach($exams as $exam){
                $subject=subject::create([
                    'user_id'=>Auth::id(),
                    'sub_title'=>$exam['question'],
                    'correct_ans'=>$exam['answer'],
                    'subject_id'=>$subbtitlei_id,
                ]);
                $insertid=$subject->id;
                $subjectids[]=$insertid;

                foreach($exam["options"] as $index=>$option){
                    $question=new question();
                    $question->question_section=$alphakey[$index];
                    $question->question_title=$option;
                    $question->subject_id=$insertid;
                    $question->save();
                }
            }

            userLog::create([
                'user_id'=>Auth::id(),
                'action'=>'Added Subject '.$request->subject_title,
                'data'=>[
                    'subject'=>[
                        'subject_ids'=>$subjectids
                    ],
                ]

            ]);

            return redirect()->route('exam.index')->with('success','exam question successfully uploaded');
        }
        return redirect()->route('exam.index')->with('success','File uploaded successfully');
    }

    public function search(Request $request){
        
        $search=$request->input('search');
        $subjects=subject::find($search);
        
        return view('index',compact('subjects'));
    }



    
    public function delete(request $request)
    {

        $deleterow=$request->input('checkid');
        
        foreach($deleterow as $deleterowid){

            $subject=subject::find($deleterowid);
            $subject->delete();
        }
        return redirect()->route('exam.index')->with('success','exam question successfully deleted');
    }



}
