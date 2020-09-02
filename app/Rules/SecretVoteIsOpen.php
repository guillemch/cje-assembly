<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;
use App\SecretVote;
use App\SecretVoteRoll;

class SecretVoteIsOpen implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value)) return false;

        foreach($value as $secretVoteId => $selection) {
            // Ignore if no votes
            $totalVotes = array_sum(array_column($selection, null));
            if ($totalVotes === 0) continue;

            $secretVote = SecretVote::find($secretVoteId);
            if (!$secretVote) return false;

            $hasVoted = SecretVoteRoll::where('user_id', Auth::user()->id)
                ->where('secret_vote_id', $secretVoteId)->count();

            if ($secretVote->open !== 1 || $hasVoted !== 0) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La votación está cerrada o ya has votado.';
    }
}
