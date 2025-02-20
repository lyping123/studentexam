<?php

namespace App\Http\Controllers;

use App\Models\subject;
use App\Models\userLog;

use Illuminate\Http\Request;

class logController extends Controller
{
    public function showlog()
    {    
        $logs=userLog::all();
        return view('log',compact('logs'));
    }

    public function undo($log_id){
        $log=userLog::find($log_id);
        if($log){
            $data=$log->data;
            
            foreach($data as $key=>$value){
                $modelClass = "App\\Models\\".$key;
                $model=new $modelClass;
                
                $model->whereIn('id',$value['subject_ids'])->delete();
                
            }
            $log->delete();
            return redirect()->route('user.showlog');
        }
        return redirect()->route('user.showlog')->withErrors('Invalid Log');
    }
}
