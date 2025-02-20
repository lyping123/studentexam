<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subject_title extends Model
{
    protected $fillable = ['subject_name'];

    public function subject(){
        return $this->hasMany(subject::class,"subject_id");
    }
}
