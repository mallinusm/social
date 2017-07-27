<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;

/**
 * Class UpdateUserAction
 * @package Social\Http\Actions\Users
 */
final class UpdateUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdateUserSettingsAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $userId = $request->user()->getId();

        $this->validate($request, [
            'name' => 'required_without_all:email,username|string|max:255',
            'email' => 'required_without_all:name,username|string|email|max:255|unique:users,email,' . $userId,
            'username' => 'required_without_all:name,email|string|max:255|unique:users,username,' . $userId,
        ]);

        $this->userRepository->update(
            $userId, $request->input('username'), $request->input('name'), $request->input('email')
        );

        return ['message' => 'User updated.'];
    }
}
