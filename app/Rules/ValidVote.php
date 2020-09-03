<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Amendment;

class ValidVote implements Rule
{
    protected $validOptions = ['option_1', 'option_2', 'option_3', 'option_4', 'option_5'];

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

        // Check user is not casting more votes than they are allowed
        foreach($value as $amendmentId => $selection) {
            // Check secret_vote_id exists
            $amendment = Amendment::find($amendmentId);
            if (!$amendment) return false;

            $totalVotes = array_sum(array_column($selection, null));
            if ($totalVotes > $userVotes) return false;

            // Check that options are valid
            foreach($selection as $option => $votes) {
                if (!in_array($option, $this->validOptions) || (!$amendment[$option] && $votes > 0)) {
                    return false;
                }
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
        return 'Voto inválido.';
    }
}
