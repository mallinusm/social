<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Exception;
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
     * @throws Exception
     */
    public function react(int $reactionId, int $userId, int $reactionableId, string $reactionableType): Reactionable
    {
        $result = $this->getSqlQueryBuilder()
            ->insert('reactionables')
            ->values([
                'reaction_id' => '?',
                'user_id' => '?',
                'reactionable_id' => '?',
                'reactionable_type' => '?',
                'created_at' => '?',
                'updated_at' => '?',
            ])
            ->setParameter(0, $reactionId)
            ->setParameter(1, $userId)
            ->setParameter(2, $reactionableId)
            ->setParameter(3, $reactionableType)
            ->setParameter(4, $now = $this->freshTimestamp())
            ->setParameter(5, $now)
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the reactionable.');
        }

        return (new Reactionable)->setId($this->lastInsertedId())
            ->setReactionId($reactionId)
            ->setUserId($userId)
            ->setReactionableId($reactionableId)
            ->setReactionableType($reactionableType)
            ->setCreatedAt($now = $this->freshTimestamp())
            ->setUpdatedAt($now);
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
