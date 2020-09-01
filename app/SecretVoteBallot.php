<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecretVoteBallot extends Model
{
    /**
     * Get the vote that the ballot belongs to
     */
    public function secret_vote()
    {
        return $this->belongsTo('App\SecretVote');
    }
}
