<?php

namespace Social\Repositories;

use Social\Entities\Follower;

/**
 * Class DoctrineFollowerRepository
 * @package Social\Repositories
 */
final class DoctrineFollowerRepository extends DoctrineRepository
{
    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function isFollowing(int $authorId, int $userId): bool
    {
        // TODO use EXISTS expr
        return $this->getEntityManager()
                ->getRepository(Follower::class)
                ->findOneBy(compact('authorId', 'userId')) !== null;
    }
}
