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
     * @return User
     */
    public function register(string $email, string $name, string $password): User
    {
        $now = $this->freshTimestamp();

        return $this->persist(
            (new User)->setEmail($email)
                ->setName($name)
                ->setPassword($password)
                ->setCreatedAt($now)
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
        return (bool) $this->entityManager
            ->createQueryBuilder()
            ->update(User::class, 'u')
            ->where('u.id = ?1')
            ->setParameter(1, $userId)
            ->set('u.avatar', '?2')
            ->setParameter(2, $avatar)
            ->getQuery()
            ->execute();
    }
}
