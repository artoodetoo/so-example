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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function related_post()
    {
        return $this->belongsTo(Post::class, 'related_post_id');
    }
}
