<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    protected $table = 'post_history';

    protected $fillable = [
        'history_type_id',
        'post_id',
        'revision_guid',
        'user_id',
        'user_name',
        'comment',
        'body',
    ];

    public function setRevisionGuidAttribute($value)
    {
        $this->attributes['uuid'] = uuid2bin($value);
    }

    public function getRevisionGuidAttribute($value)
    {
        return bin2uuid($value);
    }

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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
