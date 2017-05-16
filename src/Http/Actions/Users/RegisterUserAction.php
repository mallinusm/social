<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\User;

/**
 * Class RegisterUserAction
 * @package Social\Http\Actions\Users
 */
class RegisterUserAction
{
    use ValidatesRequests;

    /**
     * @param Request $request
     * @return User
     */
    public function __invoke(Request $request): User
    {
        $this->validate($request, User::$createRules);

        return User::create($request->only('name', 'email', 'password'));
    }
}