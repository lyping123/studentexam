<?php

namespace App\Http\Controllers;

use App\Models\question;
use App\Models\subject;
use Illuminate\Http\Request;

class examController extends Controller
{
    public function index(){
        $subjects=subject::all();
        return view('index',compact('subjects'));
    }
    public function uploadJson(Request $request){
        $request->validate([
            "subject_title"=>'required',
            'jsonFile' => 'required|mimes:json'

        ]);

        if($request->hasFile('jsonFile')){
            $jsonFile = $request->file('jsonFile');
            $jsonData = file_get_contents($jsonFile->getPathname());

            $exams=json_decode($jsonData,true);
            if(!$exams){
                return redirect()->route('exam.index')->withErrors('Invalid Json File');
            }
            $alphakey=range('A','Z');
            foreach($exams as $exam){
                $subject=subject::create([
                    'user_id'=>1,
                    'sub_title'=>$exam['question'],
                    'correct_ans'=>$exam['answer'],
                    'subject'=>$request->subject_title,
                ]);
                $insertid=$subject->id;

                foreach($exam["options"] as $index=>$option){
                    $question=new question();
                    $question->question_section=$alphakey[$index];
                    $question->question_title=$option;
                    $question->subject_id=$insertid;
                    $question->save();
                }
            }

            return redirect()->route('exam.index')->with('success','exam question successfully uploaded');
        }
        return redirect()->route('exam.index')->with('success','File uploaded successfully');
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
