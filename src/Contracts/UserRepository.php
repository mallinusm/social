<?php

namespace Social\Contracts;

use Social\Models\User;

/**
 * Interface UserRepository
 * @package Social\Contracts
 */
interface UserRepository
{
    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @return User
     */
    function register(string $email, string $name, string $password): User;
}
