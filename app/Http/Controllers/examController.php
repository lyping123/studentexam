<?php

namespace App\Http\Controllers;

use App\Models\exam_question;
use App\Models\question;
use App\Models\question_paper;
use App\Models\subject;
use App\Models\subject_title;
use App\Models\userLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class examController extends Controller
{
    public function index(Request $request)
    {
        $subject_titles=subject_title::all();
        $search=$request->input("search");
        $subjects=subject::filter($search)->get();
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

    public function setquestion(Request $request)
    {
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
            "paper_name"=>"required"
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
            return redirect()->route("exam.stuquestiton")->with("success","set question addded success");
        }

        return redirect()->route("exam.stuquestiton")->withErrors("set question addded fail");

    }
    public function deletesetupAll()
    {
        $examquestions=exam_question::where("user_id",Auth::id());
        $examquestions->delete();
        return redirect()->route("exam.stuquestiton")->with("success","exam question deleted success");
    }

    public function deletesetup(exam_question $exam_question)
    {
        $exam_question->delete();
        return redirect()->route("exam.stuquestiton")->with("success","exam question deleted success");
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
