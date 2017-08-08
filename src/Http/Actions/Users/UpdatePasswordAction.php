<?php

namespace Social\Http\Actions\Users;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Models\User;

/**
 * Class UpdatePasswordAction
 * @package Social\Http\Actions\Users
 */
final class UpdatePasswordAction
{
    use ValidatesRequests;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdatePasswordAction constructor.
     * @param Hasher $hasher
     * @param UserRepository $userRepository
     */
    public function __construct(Hasher $hasher, UserRepository $userRepository)
    {
        $this->hasher = $hasher;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'old_password' => 'required|string|min:6|max:255',
            'password' => 'required|string|min:6|max:255|confirmed'
        ]);

        /* @var User $user */
        $user = $request->user();

        if (! $this->hasher->check($request->input('old_password'), $user->getPassword())) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $password = $this->hasher->make($request->input('password'));

        $this->userRepository->updatePassword($user->getId(), $password);

        return ['message' => 'Password updated.'];
    }
}
