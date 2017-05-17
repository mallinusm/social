<?php

namespace Social\Policies;

use Social\Models\{
    Post,
    User
};

/**
 * Class PostPolicy
 * @package Social\Policies
 */
class PostPolicy
{
    /**
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        $userId = (int)($user->getAuthIdentifier());

        return $userId === $post->getAuthorId() || $userId === $post->getUserId();
    }
}