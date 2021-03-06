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

    /**
     * Get joint amendments
     */
    public function joint_amendments()
    {
        return $this->hasMany(Self::class, 'joint_with');
    }

    /**
     * Opens a vote on an amendment
     */
    public function openVote()
    {
        $this->closeAllVotes();
        $openTime = Carbon::now();
        $ref = $this->joint_with ?: $this->id;
        $jointAmendments = Self::where('joint_with', $ref)->orWhere('id', $ref)->get();

        foreach($jointAmendments as $amendment) {
            $amendment->open = 1;
            $amendment->opened_at = $openTime;
            $amendment->save();
        }
    }

    /**
     * Closes the vote on an amendment
     */
    public function closeVote()
    {
        $this->closeAllVotes();
        $closeTime = Carbon::now();
        $ref = $this->joint_with ?: $this->id;
        $jointAmendments = Self::where('joint_with', $ref)->orWhere('id', $ref)->get();

        foreach($jointAmendments as $amendment) {
            $amendment->closed_at = $closeTime;
            $amendment->save();
        }
    }

    /**
     * Filter open votes
     */
    public function scopeOpen($query)
    {
        return $query->where('open', '=', 1);
    }

    /**
     * Results attribute
     */
    public function getResultsAttribute() {
        return Self::results($this->attributes, false);
    }

    /**
     * Generates the results for an amendment
     */
    public static function results($amendment, $full = false)
    {
        $options = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $votes = Vote::select('votes.id', 'votes.user_id', 'votes.amendment_id', 'votes.vote_for', 'users.type', 'users.name', 'users.last_name', 'users.group_id')
            ->where('amendment_id', $amendment['id'])
            ->join('users', 'users.id', '=', 'votes.user_id')
            ->orderBy('votes.id', 'asc')
            ->orderBy('votes.vote_for', 'asc')
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

        $unique = [];
        $participation = [1 => 0, 2 => 0];
        $totals = [1 => $options, 2 => $options];
        foreach($votes as $vote) {
            $unique[$vote->user_id] = 1;
            $participation[$vote->type]++;
            $totals[$vote->type][$vote->vote_for]++;

            if ($vote->group_id) {
                $byGroup[$vote->group_id]['votes'][$vote->vote_for]++;
            }
        }

        $total = $participation[1] + $participation[2];
        $percentage = ($total > 0) ? $participation[1] / $total : 0.5;
        if ($percentage < 0.4) {
            $compensate = 1;
        } elseif($percentage > 0.6) {
            $compensate = 2;
        } else {
            $compensate = false;
        }

        // Weighted results
        $weighted = $options;
        foreach($options as $option => $votes) {
            if ($compensate == 1) {
                $result = ($participation[1] > 0 && $participation[2] > 0) ? ((($totals[2][$option] * (0.6 / $participation[2])) + ($totals[1][$option] * (0.4 / $participation[1]))) * 100) : 0;
            } elseif($compensate == 2) {
                $result = ($participation[1] > 0 && $participation[2] > 0) ? ((($totals[2][$option] * (0.4 / $participation[2])) + ($totals[1][$option] * (0.6 / $participation[1]))) * 100) : 0;
            } else {
                $result = ($total > 0) ? ((($totals[2][$option] + $totals[1][$option]) / $total) * 100) : 0;
            }

            $weighted[$option] = round($result, 2);
        }

        // Winner
        if ($amendment['option_3'] == 'Abstención') {
            if ($amendment['vote_type'] === 'absolute' && count($unique) > 0) {
                if ($weighted[1] > 50) {
                    $winner = 1;
                } else {
                    $winner = 2;
                }
            } else {
                $difference = $weighted[1] - $weighted[2];
                if ($difference > 0) {
                    $winner = 1;
                } elseif($difference < 0) {
                    $winner = 2;
                } else {
                    $winner = 0;
                }
            }
        } else {
            $sortedByScore = $weighted;
            rsort($sortedByScore);
            $highestScore = ($sortedByScore[0] === $sortedByScore[1]) ? 0 : $sortedByScore[0];
            $winner = ($highestScore > 0) ? array_search($highestScore, $weighted) : 0;
        }

        $results = [
            'weighted' => $weighted,
            'absolute' => $totals,
            'winner' => $winner,
            'by_group' => $byGroup,
            'total' => array_sum($totals[1]) + array_sum($totals[2]),
            'unique' => count($unique),
            'compensate' => $compensate
        ];

        if ($full) {
            $results['full'] = $votes;
        }

        return $results;
    }

    /**
     * Closes all votes
     */
    private function closeAllVotes()
    {
        return DB::table('amendments')->update(['open' => 0]);
    }
}
