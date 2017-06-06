<?php

namespace Social\Policies;

use Social\Models\{
    Follower, User
};

/**
 * Class FollowerPolicy
 * @package Social\Policies
 */
class FollowerPolicy
{
    /**
     * @param User $user
     * @param Follower $follower
     * @return bool
     */
    public function delete(User $user, Follower $follower): bool
    {
        return $user->getId() === $follower->getAuthorId();
    }
}