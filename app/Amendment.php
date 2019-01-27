<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Vote;
use App\Group;
use Carbon\Carbon;

class Amendment extends Model
{
    /**
     * Append results to the amendment
     */
    protected $appends = ['results'];

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
        $this->opened_at = Carbon::now();
        return $this->save();
    }
    
    public function closeVote()
    {
        $this->closeAllVotes();
        $this->closed_at = Carbon::now();
        return $this->save();
    }

    public function scopeCurrent($query)
    {
        return $query->where('open', '=', 1)
                     ->limit(1);
    }

    public function getResultsAttribute() {
        return Self::results($this->attributes['id'], false);
    }

    public static function results($id, $full = false)
    {
        $options = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $votes = Vote::select('votes.id', 'votes.amendment_id', 'votes.vote_for', 'users.type', 'users.name', 'users.last_name', 'users.group_id')
            ->where('amendment_id', $id)
            ->join('users', 'users.id', '=', 'votes.user_id')
            ->orderBy('votes.id', 'asc')
            ->get();
        
        $groups = Group::orderBy('acronym', 'asc')->get();
        $byGroup = [];
        foreach($groups as $group) {
            $byGroup[$group->id] = [
                'name' => $group->name,
                'acronym' => $group->acronym,
                'votes' => $options
            ];
        }

        $absolute = [1 => $options, 2 => $options];
        foreach($votes as $vote) {
            $absolute[$vote->type][$vote->vote_for]++;

            if ($vote->group_id) {
                $byGroup[$vote->group_id]['votes'][$vote->vote_for]++;
            }
        }

        $weighted = $options;
        foreach($options as $option => $optionWeight) {
            $type1Votes = $absolute[1][$option];
            $type2Votes = $absolute[2][$option];

            $weightedResultType1 = ($type1Votes > 0) ? (($type1Votes / array_sum($absolute[1])) * 50) : 0;
            $weightedResultType2 = ($type2Votes > 0) ? (($type2Votes / array_sum($absolute[2])) * 50) : 0;

            $weighted[$option] = round($weightedResultType1 + $weightedResultType2, 2);
        }

        $sortedByScore = $weighted;
        rsort($sortedByScore);
        $highestScore = ($sortedByScore[0] === $sortedByScore[1]) ? 0 : $sortedByScore[0];
        $winner = ($highestScore > 0) ? array_search($highestScore, $weighted) : 0;

        $results = [
            'weighted' => $weighted,
            'absolute' => $absolute,
            'winner' => $winner,
            'by_group' => $byGroup,
            'total' => array_sum($absolute[1]) + array_sum($absolute[2])
        ];

        if ($full) {
            $results['full'] = $votes;
        }

        return $results;
    }

    private function closeAllVotes()
    {
        return DB::table('amendments')->update(['open' => 0]);
    }
}
