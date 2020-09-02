<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;
use App\SecretVote;
use App\SecretVoteOption;

class SecretVoteIsValid implements Rule
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
        $userVotes = Auth::user()->votes;

        foreach($value as $secretVoteId => $selection) {
            // Check secret_vote_id exists
            $secretVote = SecretVote::find($secretVoteId);
            if (!$secretVote) return false;

            // Check user is not casting more votes than they are allowed
            $totalVotes = array_sum(array_column($selection, null));
            if ($totalVotes > $userVotes) return false;

            // Check every option in array exists and is part of vote_id
            foreach($selection as $optionId => $votes) {
                $option = SecretVoteOption::find($optionId);
                if (!$option || $option->secret_vote_id != $secretVoteId) return false;
            }
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
        return 'La papeleta no es válida.';
    }
}
