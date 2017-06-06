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
    public function follow(int $authorId, int $userId): Follower;

    /**
     * @param int $id
     * @return bool
     */
    public function unfollow(int $id): bool;

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function isFollowing(int $authorId, int $userId): bool;
}
