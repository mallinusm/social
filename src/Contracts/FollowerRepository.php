<?php

namespace Social\Contracts;

use Social\Models\Follower;

/**
 * Interface FollowerRepository
 * @package Social\Contracts
 */
interface FollowerRepository
{
    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     */
    function follow(int $authorId, int $userId): Follower;

    /**
     * @param int $id
     * @return bool
     */
    function unfollow(int $id): bool;

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    function isFollowing(int $authorId, int $userId): bool;
}