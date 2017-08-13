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
                'reaction_id' => ':reactionId',
                'user_id' => ':userId',
                'reactionable_id' => ':reactionableId',
                'reactionable_type' => ':reactionableType',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'reactionId' => $reactionId,
                'userId' => $userId,
                'reactionableId' => $reactionableId,
                'reactionableType' => $reactionableType,
                'now' => $now = $this->freshTimestamp()
            ])
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the reactionable.');
        }

        return (new Reactionable)->setId($this->lastInsertedId())
            ->setReactionId($reactionId)
            ->setUserId($userId)
            ->setReactionableId($reactionableId)
            ->setReactionableType($reactionableType)
            ->setCreatedAt($now)
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
        $result = $this->getEntityManager()
            ->getRepository(Reactionable::class)
            ->findOneBy([
                'reactionId' => $reactionId,
                'userId' => $userId,
                'reactionableId' => $reactionableId,
                'reactionableType' => $reactionableType
            ]);

        return $result !== null;
    }

    /**
     * @param int $id
     * @return Reactionable
     * @throws EntityNotFoundException
     */
    public function find(int $id): Reactionable
    {
        $reactionable = $this->getEntityManager()->getRepository(Reactionable::class)->find($id);

        if ($reactionable === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Reactionable::class, []);
        }

        /* @var Reactionable $reactionable */
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
