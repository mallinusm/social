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
}
