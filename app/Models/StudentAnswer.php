<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable=["student_id","subject_id","answer","paper_id","attempt_id"];

    public function ExamAttempt(){
        return $this->belongsTo(ExamAttempt::class);
    }
    public function subject(){
        return $this->belongsTo(subject::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    
}
