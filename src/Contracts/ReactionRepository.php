<?php

namespace Social\Contracts;

use Social\Models\Reactionable;

/**
 * Interface ReactionRepository
 * @package Social\Contracts
 */
interface ReactionRepository
{
    /**
     * @param string $name
     * @return int
     */
    function getReactionId(string $name): int;

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionId
     * @param int $userId
     * @return bool
     */
    function hasReacted(int $reactionableId, string $reactionableType, int $reactionId, int $userId): bool;

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionId
     * @param int $userId
     * @return Reactionable
     */
    function react(int $reactionableId, string $reactionableType, int $reactionId, int $userId): Reactionable;
}
