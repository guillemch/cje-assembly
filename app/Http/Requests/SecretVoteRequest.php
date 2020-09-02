<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\SecretVote;
use App\Rules\SecretVoteIsOpen;
use App\Rules\SecretVoteIsValid;

class SecretVoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $checkedIn = $this->user()->credentials_pickedup_at !== null; 

        return $checkedIn;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selected' => [
                'required',
                new SecretVoteIsOpen(),
                new SecretVoteIsValid()
            ]
        ];
    }
}
