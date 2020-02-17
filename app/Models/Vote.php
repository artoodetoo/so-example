<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    const VOTE_ACCEPTED = 1;
    const VOTE_UP = 2 ;
    const VOTE_DOWN = 3;
    const VOTE_OFFENSIVE = 4;
    const VOTE_FAVORITE = 5;
    const VOTE_CLOSE = 6;
    const VOTE_REOPEN = 7; // not exists
    const VOTE_BOUNTY_START = 8;
    const VOTE_BOUNTY_CLOSE = 9;
    const VOTE_DELETION = 10;
    const VOTE_UNDELETION = 11;
    const VOTE_SPAM = 12;
    const VOTE_REVIEW = 15;
    const VOTE_APPROVE_EDIT = 16;

    protected $fillable = [
        'post_id',
        'vote_type_id',
        'user_id',
        'bounty_amount',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
