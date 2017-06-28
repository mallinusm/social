<?php

namespace Social\Contracts;

use Social\Entities\User;

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
     * @param string $username
     * @return User
     */
    function register(string $email, string $name, string $password, string $username): User;

    /**
     * @param int $id
     * @param string $avatar
     * @return bool
     */
    function updateAvatar(int $id, string $avatar): bool;
}
