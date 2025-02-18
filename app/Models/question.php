<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    protected $fillable = ['question_section','question_title','subject_id'];
    
    public function subject(){
        return $this->belongsTo(subject::class);
    }
}
