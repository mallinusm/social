<?php

namespace Social\Contracts;

use Social\Entities\Follower;

/**
 * Interface FollowerRepository
 * @package Social\Contracts
 */
interface FollowerRepository
{
    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    function isFollowing(int $authorId, int $userId): bool;

    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     */
    function follow(int $authorId, int $userId): Follower;

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    function unfollow(int $authorId, int $userId): bool;

    /**
     * @param int $authorId
     * @return int[]
     */
    function getFollowingIds(int $authorId): array;
}
