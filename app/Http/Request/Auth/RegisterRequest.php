<?php

namespace App\Request;

class RegisterRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|string',
            'confirm' => 'required|string|same:password'
        ];
    }
}
