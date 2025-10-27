<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

class question_paper extends Model
{
    protected $fillable=["paper_name","start_datetime","limit_submit","limit_submit_per_day","random_status","time_limit","status"];
    
    public function exam_question(){
        return $this->hasMany(exam_question::class,"paper_id");
    }
    public function exam_attempt(){
        return $this->hasMany(ExamAttempt::class,"paper_id");
    }

    public static function booted(){
        // $allSubjects = Subject::withoutGlobalScope('user')->get();
        static::addGlobalScope('user',function(Builder $builder){
            if(Auth::check()){
                if(Auth::user()->role == 'admin'){
                    $builder->whereHas("exam_question",function($query){
                        $query->where("user_id",Auth::id());  
                    });  
                }
                return ;
                
            }
        });
    }

    protected $casts = [
        'start_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
