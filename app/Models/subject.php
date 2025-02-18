<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
    protected $fillable = ['user_id','sub_title','correct_ans','subject'];

    public function questions(){
        return $this->hasMany(question::class);
    }
}
