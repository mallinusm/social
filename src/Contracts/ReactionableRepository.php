<?php

namespace Social\Contracts;

use Doctrine\ORM\EntityNotFoundException;
use Social\Entities\Reactionable;

/**
 * Interface ReactionableRepository
 * @package Social\Contracts
 */
interface ReactionableRepository
{
    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return Reactionable
     */
    function react(int $reactionId, int $userId, int $reactionableId, string $reactionableType): Reactionable;

    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return bool
     */
    function hasReacted(int $reactionId, int $userId, int $reactionableId, string $reactionableType): bool;

    /**
     * @param int $id
     * @return Reactionable
     * @throws EntityNotFoundException
     */
    function find(int $id): Reactionable;

    /**
     * @param Reactionable $reactionable
     * @return void
     */
    function delete(Reactionable $reactionable): void;
}
