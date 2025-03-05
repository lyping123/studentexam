<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
        $this->belongsTo(User::class,"student_id","id");
    }

    
   
}
