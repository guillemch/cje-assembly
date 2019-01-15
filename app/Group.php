<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Get the users that belong to the group
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
