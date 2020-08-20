<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Amendment;

class ValidVote implements Rule
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

        foreach($value as $amendmentId => $selection) {
            $totalVotes = array_sum(array_column($selection, null));
            if ($totalVotes > $userVotes) return false;
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
