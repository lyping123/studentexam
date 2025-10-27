<?php

namespace App\Models;

use App\Http\Middleware\checkauth;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class subject extends Model
{
    protected $fillable = ['user_id','sub_title','sub_content','correct_ans','subject_id'];

    // ✔ booted() → Runs when the model is used.
    // ✔ addGlobalScope('user', function (Builder $builder) { ... }) → Automatically filters user_id.
    // ✔ Auth::check() → Ensures a user is logged in before applying the filter.

    

    public function scopeFilter($query,$search)
    {
        if(!empty($search)){
            return $query->where('subject_id',$search);
        }
        return $query->where("id",0);
    }
    
    public function questions(){
        return $this->hasMany(question::class);
    }

    public function exam_question(){
        return $this->hasMany(question::class,"subject_id");
    }

    public function subject_title(){
        return $this->belongsTo(subject_title::class,"subject_id");
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
