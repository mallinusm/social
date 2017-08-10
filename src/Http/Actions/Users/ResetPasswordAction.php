<?php

namespace Social\Http\Actions\Users;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;

/**
 * Class ResetPasswordAction
 * @package Social\Http\Actions\Users
 */
final class ResetPasswordAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * ResetPasswordAction constructor.
     * @param UserRepository $userRepository
     * @param Hasher $hasher
     */
    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'token' => 'required|string|min:100|max:100|exists:password_resets,token',
            'password' => 'required|string|min:6|max:255|confirmed'
        ]);

        $password = $this->hasher->make($request->input('password'));

        if (! $this->userRepository->resetPassword($request->input('token'), $password)) {
            throw new AuthorizationException('The token has expired.');
        }

        return ['message' => 'Password was reset.'];
    }    
}
