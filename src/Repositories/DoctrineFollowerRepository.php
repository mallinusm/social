<?php

namespace Social\Repositories;

use Exception;
use Social\Contracts\Repositories\FollowerRepository;
use Social\Entities\Follower;

/**
 * Class DoctrineFollowerRepository
 * @package Social\Repositories
 */
final class DoctrineFollowerRepository extends DoctrineRepository implements FollowerRepository
{
    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function isFollowing(int $authorId, int $userId): bool
    {
        $result = $this->getEntityManager()
            ->getRepository(Follower::class)
            ->findOneBy([
                'authorId' => $authorId,
                'userId' => $userId
            ]);

        return $result !== null;
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     * @throws Exception
     */
    public function follow(int $authorId, int $userId): Follower
    {
        $result = $this->getSqlQueryBuilder()
            ->insert('followers')
            ->values([
                'author_id' => ':authorId',
                'user_id' => ':userId',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'authorId' => $authorId,
                'userId' => $userId,
                'now' => $now = $this->freshTimestamp()
            ])
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the post.');
        }

        return (new Follower)->setId($this->lastInsertedId())
            ->setAuthorId($authorId)
            ->setUserId($userId)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function unfollow(int $authorId, int $userId): bool
    {
        $expression = $this->getDqlExpression();

        return (bool) $this->getDqlQueryBuilder()
            ->delete(Follower::class, 'f')
            ->where($expression->eq('f.authorId', ':authorId'))
            ->andWhere($expression->eq('f.userId', ':userId'))
            ->setParameters([
                'authorId' => $authorId,
                'userId' => $userId
            ])
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $authorId
     * @return int[]
     */
    public function getFollowingIds(int $authorId): array
    {
        $userIds = $this->getDqlQueryBuilder()
            ->select('f.userId')
            ->from(Follower::class, 'f')
            ->where($this->getDqlExpression()->eq('f.authorId', ':authorId'))
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->execute();

        return array_column($userIds, 'userId');
    }

    /**
     * @param int $userId
     * @return Follower[]
     */
    public function getFollowers(int $userId): array
    {
        return $this->getDqlQueryBuilder()
            ->select(['f', 'a'])
            ->from(Follower::class, 'f')
            ->where($this->getSqlExpression()->eq('f.userId', ':userId'))
            ->setParameter('userId', $userId)
            ->leftJoin('f.author', 'a')
            ->getQuery()
            ->execute();
    }
}
