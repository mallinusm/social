<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Illuminate\Support\{
    Collection,
    Str
};
use Social\Contracts\UserRepository;
use Social\Entities\{
    PasswordReset,
    User
};

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
     * @throws Exception
     */
    public function register(string $email, string $name, string $password, string $username): User
    {
        $result = $this->getSqlQueryBuilder()
            ->insert('users')
            ->values([
                'email' => ':email',
                'name' => ':name',
                'username' => ':username',
                'password' => ':password',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'email' => $email,
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'now' => $now = $this->freshTimestamp()
            ])
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the user.');
        }

        return (new User)->setId($this->lastInsertedId())
            ->setEmail($email)
            ->setName($name)
            ->setPassword($password)
            ->setUsername($username)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
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
            ->set('u.updatedAt', ':now')
            ->setParameters([
                'avatar' => $avatar,
                'now' => $this->freshTimestamp()
            ])
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

        $user = $repository->findOneBy([
            'username' => $username
        ]);

        if ($user === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($repository->getClassName(), []);
        }

        /* @var User $user */
        return $user;
    }

    /**
     * @param string $payload
     * @param int $authorId
     * @return User[]
     */
    public function search(string $payload, int $authorId): array
    {
        $expression = $this->getDqlExpression();

        return $this->getDqlQueryBuilder()
            ->select(['u', 'f', 'f2'])
            ->from(User::class, 'u')
            ->leftJoin('u.followers', 'f', Join::WITH, $expression->eq('f.authorId', ':authorId'))
            ->leftJoin('u.followings', 'f2', Join::WITH, $expression->eq('f2.userId', ':authorId'))
            ->where($expression->like($expression->lower('u.name'), ':payload'))
            ->orWhere($expression->like($expression->lower('u.username'), ':payload'))
            ->setParameters([
                'payload' => $this->like($payload),
                'authorId' => $authorId
            ])
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
            ->set('u.updatedAt', ':now')
            ->setParameter('now', $this->freshTimestamp());

        (new Collection([
            'username' => $username,
            'name' => $name,
            'email' => $email
        ]))->each(function(?string $value, string $attribute) use ($dqlQueryBuilder): void {
            if (is_null($value)) {
                return;
            }

            $dqlQueryBuilder->set('u.' . $attribute, ':' . $attribute)->setParameter($attribute, $value);
        });

        return (bool) $dqlQueryBuilder->getQuery()->execute();
    }

    /**
     * @param string $email
     * @return string
     * @throws Exception
     */
    public function generatePasswordResetToken(string $email): string
    {
        $token = Str::random(100);

        $result = (int) $this->getSqlQueryBuilder()
            ->insert('password_resets')
            ->values([
                'email' => ':email',
                'token' => ':token',
                'created_at' => ':createdAt'
            ])
            ->setParameters([
                'email' => $email,
                'token' => $token,
                'createdAt' => $this->freshTimestamp()
            ])
            ->execute();

        if ($result !== 1) {
            throw new Exception('Unable to insert record.');
        }

        return $token;
    }

    /**
     * @param string $token
     * @return PasswordReset
     * @throws EntityNotFoundException
     */
    private function getPasswordResetByToken(string $token): PasswordReset
    {
        $repository = $this->getEntityManager()->getRepository(PasswordReset::class);

        $passwordReset = $repository->findOneBy([
            'token' => $token
        ]);

        if ($passwordReset === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($repository->getClassName(), []);
        }

        /* @var PasswordReset $passwordReset */
        return $passwordReset;
    }

    /**
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function resetPassword(string $token, string $password): bool
    {
        $passwordReset = $this->getPasswordResetByToken($token);

        if (time() > ($passwordReset->getCreatedAt() + (60 * 60))) {
            /**
             * The token has expired.
             */
            return false;
        }

        $email = $passwordReset->getEmail();

        $this->remove($passwordReset);

        return (bool) $this->getDqlQueryBuilder()
            ->update(User::class, 'u')
            ->where($this->getDqlExpression()->eq('u.email', ':email'))
            ->set('u.password', ':password')
            ->setParameters([
                'password' => $password,
                'email' => $email
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function updatePassword(int $id, string $password): bool
    {
        return (bool) $this->getDqlQueryBuilder()
            ->update(User::class, 'u')
            ->where($this->getDqlExpression()->eq('u.id', ':id'))
            ->set('u.password', ':password')
            ->setParameters([
                'id' => $id,
                'password' => $password
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $username
     * @param int $authorId
     * @return User
     * @throws EntityNotFoundException
     */
    public function visitByUsername(string $username, int $authorId): User
    {
        $expression = $this->getDqlExpression();

        $user = $this->getDqlQueryBuilder()
            ->select(['u', 'f', 'f2'])
            ->from(User::class, 'u')
            ->leftJoin('u.followers', 'f', Join::WITH, $expression->eq('f.authorId', ':authorId'))
            ->leftJoin('u.followings', 'f2', Join::WITH, $expression->eq('f2.userId', ':authorId'))
            ->where($expression->eq('u.username', ':username'))
            ->setParameters([
                'username' => $username,
                'authorId' => $authorId
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($user === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(User::class, []);
        }

        return $user;
    }
}
