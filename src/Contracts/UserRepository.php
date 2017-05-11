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
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function register(string $name, string $email, string $password): User;
}