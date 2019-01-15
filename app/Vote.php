<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * Get the user that cast the vote
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the amendment that the vote is for
     */
    public function amendment()
    {
        return $this->belongsTo('App\Amendment');
    }
}
