<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userLog extends Model
{
    protected $table = "user_logs";
    protected $fillable = ['user_id','action','data'];

    protected $casts = [
        'data' => 'array'
    ];
}
