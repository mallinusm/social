<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Support\Collection;
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
        return (bool) $this->getDqlQueryBuilder()
            ->update(User::class, 'u')
            ->where($this->getDqlExpression()->eq('u.id', $userId))
            ->set('u.avatar', ':avatar')
            ->setParameter('avatar', $avatar)
            ->set('u.updatedAt', ':updatedAt')
            ->setParameter('updatedAt', $this->freshTimestamp())
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    public function findByUsername(string $username): User
    {
        $repository = $this->getEntityManager()->getRepository(User::class);

        /** @var User $user */
        $user = $repository->findOneBy(compact('username'));

        if ($user === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($repository->getClassName(), []);
        }

        return $user;
    }

    /**
     * @param string $payload
     * @return User[]
     */
    public function search(string $payload): array
    {
        $expression = $this->getDqlExpression();

        return $this->getDqlQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where($expression->like($expression->lower('u.name'), ':payload'))
            ->orWhere($expression->like($expression->lower('u.username'), ':payload'))
            ->setParameter('payload', '%' . strtolower($payload) . '%')
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $userId
     * @param null|string $username
     * @param null|string $name
     * @param null|string $email
     * @return bool
     */
    public function update(int $userId, ?string $username, ?string $name, ?string $email): bool
    {
        $dqlQueryBuilder = $this->getDqlQueryBuilder()
            ->update(User::class, 'u')
            ->where($this->getDqlExpression()->eq('u.id', $userId))
            ->set('u.updatedAt', ':updatedAt')
            ->setParameter('updatedAt', $this->freshTimestamp());

        (new Collection(compact('username', 'name', 'email')))
            ->filter(function(?string $value): bool {
                return ! is_null($value);
            })
            ->each(function(string $value, string $attribute) use ($dqlQueryBuilder): void {
                $dqlQueryBuilder->set('u.' . $attribute, ':' . $attribute)->setParameter($attribute, $value);
            });

        return (bool) $dqlQueryBuilder->getQuery()->execute();
    }
}
