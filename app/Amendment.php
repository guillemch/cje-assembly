<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Amendment extends Model
{
    /**
     * Get the votes for the amendment.
     */
    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function openVote()
    {
        $this->closeAllVotes();
        $this->open = 1;
        return $this->save();
    }

    public function scopeCurrent($query)
    {
        return $query->where('open', '=', 1)
                     ->limit(1);
    }

    private function closeAllVotes()
    {
        return DB::table('amendments')->update(['open' => 0]);
    }
}
