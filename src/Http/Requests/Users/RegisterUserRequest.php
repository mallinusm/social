<?php

namespace Social\Http\Requests\Users;

use Social\Http\Requests\Request;

/**
 * Class RegisterUserRequest
 * @package Social\Http\Requests\Users
 */
class RegisterUserRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}