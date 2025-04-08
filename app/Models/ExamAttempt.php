<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable=["student_id","paper_id"];

    public function scopeFilter($query,array $search)
    {
        
        if(!empty($search)){
            
            // $query->whereHas("question_paper",function($q) use($search){
                
            //     $q->where("paper_name","like","%$search%");
            // });
            if(request()->has("question_paper")){
                $query->whereHas("question_paper",function($q) use($search){
                    $q->where("paper_name","like","%$search[question_paper]%");
                });
            }
            if(request()->has("student")){
                $query->whereHas("user",function($q) use($search){
                    $q->where("name","like","%$search[student]%");
                });
            }
            if(request()->has("month")){
                $query->whereMonth("created_at","$search[month]");
            }
            
            // $query->orWhereHas("student",function($q) use($search){
            //     $q->where("name","like","%$search%");
            // });
        }
        return $query;
    }

    public function question_paper(){
        return $this->belongsTo(question_paper::class,"paper_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"student_id");
    }
    public function student_answer(){
        return $this->hasMany(StudentAnswer::class,"attempt_id");
    }

}
