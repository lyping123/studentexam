<?php

namespace App\Http\Controllers;

use App\Models\subject_title;
use Illuminate\Http\Request;

class ajaxController extends Controller
{
    public function getSubject(Request $request){
        
        $subject_title=$request->get('search');
        $subject_title=subject_title::where('subject_title',"like","%$subject_title%")->get();
        
        if($subject_title){
            
            return response()->json([
                "data"=>$subject_title,
                "status"=>200
            ]);
        }
        return response()->json([]);
    }
}
