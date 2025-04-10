<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\subject_title;
use App\Models\question_paper;

class ajaxController extends Controller
{
    public function getSubject(Request $request){
        
        $subject_title=$request->get('search');
        $subject_title=subject_title::where('subject_name',"like","%$subject_title%")->get();
        
        if($subject_title){
            
            return response()->json([
                "data"=>$subject_title,
                "status"=>200
            ]);
        }
        return response()->json([]);
    }

    public function getQuestionPaperSetting($id){
        $question_paper=question_paper::find($id);
        return response()->json([
            "data"=>$question_paper,
            "status"=>200
        ]);
    }

    public function studentEdit($id){
        $student=User::find($id);
        return response()->json([
            "data"=>$student,
            "status"=>200
        ]);
    }
}
