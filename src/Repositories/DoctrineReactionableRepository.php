<?php

namespace Social\Repositories;

use Social\Contracts\ReactionableRepository;
use Social\Entities\Reactionable;

/**
 * Class DoctrineReactionableRepository
 * @package Social\Repositories
 */
final class DoctrineReactionableRepository extends DoctrineRepository implements ReactionableRepository
{
    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return Reactionable
     */
    public function react(int $reactionId, int $userId, int $reactionableId, string $reactionableType): Reactionable
    {
        return $this->persist(
            (new Reactionable)->setReactionId($reactionId)
                ->setUserId($userId)
                ->setReactionableId($reactionableId)
                ->setReactionableType($reactionableType)
                ->setCreatedAt($now = $this->freshTimestamp())
                ->setUpdatedAt($now)
        );
    }

    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return bool
     */
    public function hasReacted(int $reactionId, int $userId, int $reactionableId, string $reactionableType): bool
    {
        return $this->getEntityManager()
            ->getRepository(Reactionable::class)
            ->findOneBy(compact('reactionId', 'userId', 'reactionableId', 'reactionableType')) !== null;
    }
}
