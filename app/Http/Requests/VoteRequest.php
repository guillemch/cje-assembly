<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Vote;
use App\Rules\VoteIsOpen;
use App\Rules\ValidVote;
use App\Rules\PasswordCorrect;

class VoteRequest extends FormRequest
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
            'amendment_id' => [
                'required',
                new VoteIsOpen()
            ],
            'selected' => [
                'required',
                new ValidVote($this->input('amendment_id'))
            ],
            'password' => [
                'required',
                new PasswordCorrect()
            ]
        ];
    }
}
