<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class subject_title extends Model
{
    protected $fillable = ['subject_name'];

    public function subject(){
        return $this->hasMany(subject::class,"subject_id");
    }

    public static function booted(){
        // $allSubjects = Subject::withoutGlobalScope('user')->get();
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check() && Auth::user()->role == 'admin') {
                
                $builder->whereHas("subject",function($query){
                    $query->where("user_id",Auth::id());
                });
                return $builder;
            }
        });
    }
}
