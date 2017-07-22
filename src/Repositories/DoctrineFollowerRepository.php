<?php

namespace Social\Repositories;

use Social\Contracts\FollowerRepository;
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
        return $this->getEntityManager()
                ->getRepository(Follower::class)
                ->findOneBy(compact('authorId', 'userId')) !== null;
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     */
    public function follow(int $authorId, int $userId): Follower
    {
        return $this->persist(
            (new Follower)->setAuthorId($authorId)
                ->setUserId($userId)
                ->setCreatedAt($now = $this->freshTimestamp())
                ->setUpdatedAt($now)
        );
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function unfollow(int $authorId, int $userId): bool
    {
        return (bool) $this->getDqlQueryBuilder()
            ->delete(Follower::class, 'f')
            ->where('f.authorId = ?1')
            ->setParameter(1, $authorId)
            ->andWhere('f.userId = ?2')
            ->setParameter(2, $userId)
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
            ->where('f.authorId = ?1')
            ->setParameter(1, $authorId)
            ->getQuery()
            ->execute();

        return array_column($userIds, 'userId');
    }
}
