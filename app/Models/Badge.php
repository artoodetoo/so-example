<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'badge_name',
        'user_id',
        'badge_class',
        'tag_based',
    ];
}
