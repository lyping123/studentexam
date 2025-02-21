<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class question_paper extends Model
{
    protected $fillable=["paper_name","total_question"];
    public function exam_question(){
        return $this->hasMany(exam_question::class,"paper_id");
    }
}
