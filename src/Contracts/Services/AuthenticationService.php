<?php

namespace Social\Contracts\Services;

use Social\Entities\User;
use Social\Exceptions\AuthenticationException;

/**
 * Interface AuthenticationService
 * @package Social\Contracts\Services
 */
interface AuthenticationService
{
    /**
     * @return User
     * @throws AuthenticationException
     */
    public function getAuthenticatedUser(): User;
}
