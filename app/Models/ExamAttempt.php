<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExamAttempt extends Model
{
    protected $fillable=["student_id","paper_id"];

    public function scopeFilter($query,array $search)
    {
        
        if(!empty($search)){
            
            // $query->whereHas("question_paper",function($q) use($search){
                
            //     $q->where("paper_name","like","%$search%");
            // });
            if(request()->has("question_paper") && request("question_paper"!="")){
                $query->whereHas("question_paper",function($q) use($search){
                    $q->where("paper_name","like","%$search[question_paper]%");
                });
            }
            if(request()->has("student") && request("student")!=""){
                $query->whereHas("user",function($q) use($search){
                    $q->where("name","like","%$search[student]%");
                });
            }
            if(request()->has("month") && request("month")!=""){
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
    public function student(){
        return $this->belongsTo(student::class,"student_id");
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('lecturer_scope', function ($query) {
    //         if (auth()->check() && auth()->user()->role === 'admin') {
    //             $query->whereHas('user', function ($q) {
    //                 $q->where('user_id', Auth::id())->join('students', function ($q) {
    //                     $q->where('user_id', Auth::id());
    //                 });
                
    //             });
    //         }
    //     });
    // }
    

    

}
