<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class student extends Model
{

    protected $fillable=["user_id","student_id"];
    
    public function scopeFilter($query,$search)
    {
        if(!empty($search)){
            $query->where("user_id",$search);
        }
        return $query;
    }
    public function user(){
        return $this->belongsTo(User::class,"student_id","id");
    }
    

    public static function studentGroup(){
        // Uncomment this block if you want to filter by student name
        // if($student != ""){
        //     return self::where("user_id", Auth::id())
        //         ->whereHas("user", function($query) use ($student) {
        //             $query->where("name", "like", "%$student%");
        //         })->get();
        // }
        return self::where("user_id", Auth::id())->get();
    }

    protected static function booted()
    {
        static::addGlobalScope('user', function ($builder) {
            if (auth()->check()) {
                if (Auth::user()->role === 'admin') {
                    $builder->where('user_id', Auth::id());
                }
                return ;
            }
        });
    }
}
