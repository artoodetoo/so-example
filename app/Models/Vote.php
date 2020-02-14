<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'post_id',
        'vote_type_id',
        'user_id',
        'bounty_amount',
    ];
}
