<?php

namespace Social\Contracts;

use Social\Models\Reaction;

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
    function getReactionTypeId(string $name): int;

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionTypeId
     * @param int $userId
     * @return bool
     */
    function hasReacted(int $reactionableId, string $reactionableType, int $reactionTypeId, int $userId): bool;

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionTypeId
     * @param int $userId
     * @return Reaction
     */
    function react(int $reactionableId, string $reactionableType, int $reactionTypeId, int $userId): Reaction;
}
