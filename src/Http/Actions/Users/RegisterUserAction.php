<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Models\User;

/**
 * Class RegisterUserAction
 * @package Social\Http\Actions\Users
 */
class RegisterUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RegisterUserAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return User
     */
    public function __invoke(Request $request): User
    {
        $this->validate($request, User::$createRules);

        return $this->userRepository->register(
            $request->input('email'), $request->input('name'), bcrypt($request->input('password'))
        );
    }
}