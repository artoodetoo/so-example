<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'score',
        'body',
        'user_id',
        'display_name',
    ];
}
