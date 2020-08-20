<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;
use App\Amendment;

class VoteIsOpen implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        foreach($value as $amendmentId => $selection) {
            $amendment = Amendment::find($amendmentId);
            if (!$amendment) return false;

            $hasVoted = $amendment->votes()->where('user_id', Auth::user()->id)->count();
            if ($amendment->open !== 1 || $hasVoted !== 0) return false;
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
        return 'La votación está cerrada o ya has votado en ella.';
    }
}
