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
                'acronym' => $group->acronym,
                'type' => $group->type,
                'votes' => $options
            ];
        }

        $participation = [1 => 0, 2 => 0];
        $totals = [1 => $options, 2 => $options];
        foreach($votes as $vote) {
            $participation[$vote->type]++;
            $totals[$vote->type][$vote->vote_for]++;

            if ($vote->group_id) {
                $byGroup[$vote->group_id]['votes'][$vote->vote_for]++;
            }
        }

        $percentage = $participation[1] / ($participation[1] + $participation[2]);
        $compensate = ($percentage < 0.4) ? 1 : ($percentage > 0.6) ? 2 : false;

        // Weighted results
        $weighted = $options;
        foreach($options as $option => $votes) {
            if ($compensate == 1) {
                $weighted[$option] = (($totals[2][$option] * (0.6 / $participation[2])) + ($totas[1][$option] * (0.4 / $participation[1]))) * 100;
            } elseif($compensate == 2) {
                $weighted[$option] = (($totals[2][$option] * (0.4 / $participation[2])) + ($totals[1][$option] * (0.6 / $participation[1]))) * 100;
            } else {
                $weighted[$option] = (($totals[2][$option] + $totals[1][$option]) / ($participation[2] + $participation[1])) * 100;
            }
        }

        // Winner
        $sortedByScore = $weighted;
        rsort($sortedByScore);
        $highestScore = ($sortedByScore[0] === $sortedByScore[1]) ? 0 : $sortedByScore[0];
        $winner = ($highestScore > 0) ? array_search($highestScore, $weighted) : 0;

        $results = [
            'weighted' => $weighted,
            'absolute' => $totals,
            'winner' => $winner,
            'by_group' => $byGroup,
            'total' => array_sum($totals[1]) + array_sum($totals[2]),
            'compensate' => $compensate
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
