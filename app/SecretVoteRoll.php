<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretVoteRoll extends Model
{
    protected $table = 'secret_vote_roll';

    /**
     * Get the user that cast the vote
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the related vote
     */
    public function secret_vote()
    {
        return $this->belongsTo('App\SecretVote');
    }
}
