<?php

namespace Social\Services;

use Illuminate\Contracts\Auth\Guard;
use Social\Contracts\Services\AuthenticationService;
use Social\Entities\User;
use Social\Exceptions\AuthenticationException;
use Social\Models\User as EloquentUserModel;

/**
 * Class LaravelAuthenticationService
 * @package Social\Services
 */
final class LaravelAuthenticationService implements AuthenticationService
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * AuthenticationService constructor.
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return User
     * @throws AuthenticationException
     */
    public function getAuthenticatedUser(): User
    {
        $user = $this->guard->user();

        if ($user === null) {
            throw new AuthenticationException('Cannot retrieve the current user when unauthenticated.');
        }

        /* @var EloquentUserModel $user */
        return $user->toUserEntity();
    }

    /**
     * @return int
     */
    public function getAuthenticatedUserId(): int
    {
        return $this->getAuthenticatedUser()->getId();
    }
}
