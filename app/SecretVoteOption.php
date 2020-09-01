<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretVoteOption extends Model
{
    /**
     * Get the vote that the option belongs to
     */
    public function secret_vote()
    {
        return $this->belongsTo('App\SecretVote');
    }

    /**
     * Get the ballots for the option
     */
    public function ballots()
    {
        return $this->hasMany('App\SecretVoteBallot');
    }
}
