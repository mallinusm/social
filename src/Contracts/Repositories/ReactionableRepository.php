<?php

namespace Social\Contracts\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Social\Entities\Reactionable;

/**
 * Interface ReactionableRepository
 * @package Social\Contracts
 */
interface ReactionableRepository
{
    /**
     * @var string
     */
    public const REACTIONABLE_TYPE_POSTS = 'posts';

    /**
     * @var string
     */
    public const REACTIONABLE_TYPE_COMMENTS = 'comments';

    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return Reactionable
     */
    public function react(int $reactionId, int $userId, int $reactionableId, string $reactionableType): Reactionable;

    /**
     * @param int $reactionId
     * @param int $userId
     * @param int $reactionableId
     * @param string $reactionableType
     * @return bool
     */
    public function hasReacted(int $reactionId, int $userId, int $reactionableId, string $reactionableType): bool;

    /**
     * @param int $id
     * @return Reactionable
     * @throws EntityNotFoundException
     */
    public function find(int $id): Reactionable;

    /**
     * @param Reactionable $reactionable
     * @return void
     */
    public function delete(Reactionable $reactionable): void;
}
