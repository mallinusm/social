<?php

namespace Social\Contracts;

use Doctrine\ORM\EntityNotFoundException;
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

    /**
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    function findByUsername(string $username): User;

    /**
     * @param string $payload
     * @return User[]
     */
    function search(string $payload): array;

    /**
     * @param int $userId
     * @param null|string $username
     * @param null|string $name
     * @param null|string $email
     * @return bool
     */
    function update(int $userId, ?string $username, ?string $name, ?string $email): bool;
}
