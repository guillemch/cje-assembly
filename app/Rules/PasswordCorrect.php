<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Google2FA;

class PasswordCorrect implements Rule
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
        $secret = config('google2fa.secret');
        $google2fa = app('pragmarx.google2fa');
        $google2fa->setOneTimePasswordLength(4);
        $google2fa->setKeyRegeneration(45);
        $verified = $google2fa->verifyKey($secret, $value, 1);

        return $verified;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El código introducido no es correcto. Es posible que haya cambiado. Vuelve a comprobar la pantalla.';
    }
}
