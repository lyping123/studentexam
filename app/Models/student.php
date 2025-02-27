<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class student extends Model
{

    protected $fillable=["user_id","course_id","gender"];
   
}
