<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tag_name',
        'tag_count',
        'excerpt_post_id',
        'wiki_post_id',
    ];
}
