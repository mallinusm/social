<?php

namespace Social\Repositories;

use Social\Contracts\UserRepository;
use Social\Entities\User;

/**
 * Class DoctrineUserRepository
 * @package Social\Repositories
 */
final class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @param string $username
     * @return User
     */
    public function register(string $email, string $name, string $password, string $username): User
    {
        return $this->persist(
            (new User)->setEmail($email)
                ->setName($name)
                ->setPassword($password)
                ->setUsername($username)
                ->setCreatedAt($now = $this->freshTimestamp())
                ->setUpdatedAt($now)
        );
    }

    /**
     * @param int $userId
     * @param string $avatar
     * @return bool
     */
    public function updateAvatar(int $userId, string $avatar): bool
    {
        return (bool) $this->getQueryBuilder()
            ->update(User::class, 'u')
            ->where('u.id = ?1')
            ->setParameter(1, $userId)
            ->set('u.avatar', '?2')
            ->setParameter(2, $avatar)
            ->set('u.updatedAt', '?3')
            ->setParameter(3, $this->freshTimestamp())
            ->getQuery()
            ->execute();
    }
}
