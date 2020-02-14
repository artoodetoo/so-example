<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'tags',
        'parent_id',
        'owner_id',
        'owner_name',
        'editor_id',
        'editor_name',
        'post_type_id',
        'accepted_id',
        'score',
        'view_count',
        'answer_count',
        'comment_count',
        'favorite_count',
        'activity_at',
        'closed_at',
        'owned_at',
    ];
}
