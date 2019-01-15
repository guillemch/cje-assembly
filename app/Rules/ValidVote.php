<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Amendment;

class ValidVote implements Rule
{
    /* The amendment that is being voted on */
    protected $amendment;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($amendmentId)
    {
        $this->amendment = Amendment::find($amendmentId);
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
        if (!in_array($value, [1, 2, 3, 4, 5])) return false;

        $cell = 'option_' . $value;

        return $this->amendment[$cell] !== null;
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
