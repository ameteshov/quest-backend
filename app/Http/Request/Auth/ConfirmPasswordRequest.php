<?php

namespace App\Request;

class ConfirmPasswordRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string',
            'confirm' => 'required|string|same:password',
            'hash' => 'required|string|exists:users,reset_token'
        ];
    }
}
