<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLink extends Model
{
    protected $table = 'post_links';

    protected $fillable = [
        'post_id',
        'related_post_id',
        'link_type_id',
    ];
}
