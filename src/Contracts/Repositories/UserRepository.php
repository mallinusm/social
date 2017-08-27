<?php

namespace Social\Contracts\Repositories;

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
    public function register(string $email, string $name, string $password, string $username): User;

    /**
     * @param int $id
     * @param string $avatar
     * @return bool
     */
    public function updateAvatar(int $id, string $avatar): bool;

    /**
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    public function findByUsername(string $username): User;

    /**
     * @param string $payload
     * @param int $authorId
     * @return User[]
     */
    public function search(string $payload, int $authorId): array;

    /**
     * @param int $userId
     * @param null|string $username
     * @param null|string $name
     * @param null|string $email
     * @return bool
     */
    public function update(int $userId, ?string $username, ?string $name, ?string $email): bool;

    /**
     * @param string $email
     * @return string
     */
    public function generatePasswordResetToken(string $email): string;

    /**
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function resetPassword(string $token, string $password): bool;

    /**
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function updatePassword(int $id, string $password): bool;

    /**
     * @param string $username
     * @param int $authorId
     * @return User
     * @throws EntityNotFoundException
     */
    public function visitByUsername(string $username, int $authorId): User;
}
