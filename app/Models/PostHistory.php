<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLink extends Model
{
    protected $table = 'post_history';

    protected $fillable = [
        'history_type_id',
        'post_id',
        'user_id',
        'user_name',
        'comment',
        'body',
    ];
}
