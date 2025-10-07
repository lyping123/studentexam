<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

class exam_question extends Model
{
    protected $fillable=["subject_id","user_id","paper_name"];
    
    public function subject(){
        return $this->belongsTo(subject::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function question_paper(){
        return $this->belongsTo(question_paper::class,"paper_id","id");
    }

    public static function booted(){
        // $allSubjects = Subject::withoutGlobalScope('user')->get();
        static::addGlobalScope('user',function(Builder $builder){
            if(Auth::check()){
                if(Auth::user()->role == 'admin'){
                    $builder->where("user_id",Auth::id());  
                }
                return ;
                
            }
        });
    }
}
