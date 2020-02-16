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

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function excerpt_post()
    {
        return $this->belongsTo(Post::class, 'excerpt_post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wiki_post()
    {
        return $this->belongsTo(Post::class, 'wiki_post_id');
    }
}
