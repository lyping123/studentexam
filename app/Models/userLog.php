<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class userLog extends Model
{
    protected $table = "user_logs";
    protected $fillable = ['user_id','action','data'];

    protected $casts = [
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
