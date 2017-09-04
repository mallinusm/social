<?php

namespace Social\Http\Actions\Users;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\AuthenticationService;

/**
 * Class UpdatePasswordAction
 * @package Social\Http\Actions\Users
 */
final class UpdatePasswordAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

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
     * @param AuthenticationService $authenticationService
     * @param Hasher $hasher
     * @param UserRepository $userRepository
     */
    public function __construct(AuthenticationService $authenticationService,
                                Hasher $hasher,
                                UserRepository $userRepository)
    {
        $this->authenticationService = $authenticationService;
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

        $user = $this->authenticationService->getAuthenticatedUser();

        if (! $this->hasher->check($request->input('old_password'), $user->getPassword())) {
            throw new AuthorizationException('Invalid password.');
        }

        $password = $this->hasher->make($request->input('password'));

        $this->userRepository->updatePassword($user->getId(), $password);

        return ['message' => 'Password updated.'];
    }
}
