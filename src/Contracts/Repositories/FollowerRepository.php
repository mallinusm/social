<?php

namespace Social\Contracts\Repositories;

use Social\Entities\Follower;

/**
 * Interface FollowerRepository
 * @package Social\Contracts\Repositories
 */
interface FollowerRepository
{
    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function isFollowing(int $authorId, int $userId): bool;

    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     */
    public function follow(int $authorId, int $userId): Follower;

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function unfollow(int $authorId, int $userId): bool;

    /**
     * @param int $authorId
     * @return int[]
     */
    public function getFollowingIds(int $authorId): array;

    /**
     * @param int $userId
     * @return Follower[]
     */
    public function getFollowers(int $userId): array;
}
