<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
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
     * @throws EntityNotFoundException
     */
    public function unfollow(int $authorId, int $userId): bool
    {
        $follower = $this->getEntityManager()
            ->getRepository(Follower::class)
            ->findOneBy(compact('authorId', 'userId'));
        
        if ($follower === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Follower::class, []);
        }

        $this->remove($follower);

        return true;
    }
}
