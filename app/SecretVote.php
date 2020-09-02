<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretVote extends Model
{
    /**
     * Get the options related to the vote
     */
    public function options()
    {
        return $this->hasMany('App\SecretVoteOption');
    }

    /**
     * Get the ballots related to the vote
     */
    public function ballots()
    {
        return $this->hasManyThrough('App\SecretVoteBallot', 'App\SecretVoteOption');
    }

    /**
     * Get the roll registers for the vote
     */
    public function roll()
    {
        return $this->hasMany('App\SecretVoteRoll');
    }
}
