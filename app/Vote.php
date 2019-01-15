<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    
        $this->ip = \Request::ip();
        $this->user_agent = \Request::header('User-Agent');
    }

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
