<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
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

    /**
     * @param int $id
     * @return Reactionable
     * @throws EntityNotFoundException
     */
    public function find(int $id): Reactionable
    {
        /** @var Reactionable $reactionable */
        $reactionable = $this->getEntityManager()->getRepository(Reactionable::class)->find($id);

        if ($reactionable === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Reactionable::class, []);
        }

        return $reactionable;
    }

    /**
     * @param Reactionable $reactionable
     * @return void
     */
    public function delete(Reactionable $reactionable): void
    {
        $this->remove($reactionable);
    }
}
