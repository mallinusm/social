<?php

namespace Tests\Feature;

use Social\Models\{
    Comment,
    Follower,
    PasswordReset,
    Post,
    Reactionable,
    Reaction,
    User
};

/**
 * Trait CreatesModels
 * @package Tests\Feature
 */
trait CreatesModels
{
    /**
     * @param array $attributes
     * @return User
     */
    protected function createUser(array $attributes = []): User
    {
        return factory(User::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Post
     */
    protected function createPost(array $attributes = []): Post
    {
        return factory(Post::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Comment
     */
    protected function createComment(array $attributes = []): Comment
    {
        return factory(Comment::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Follower
     */
    protected function createFollower(array $attributes = []): Follower
    {
        return factory(Follower::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Reaction
     */
    protected function createReaction(array $attributes = []): Reaction
    {
        return factory(Reaction::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Reactionable
     */
    protected function createReactionable(array $attributes = []): Reactionable
    {
        return factory(Reactionable::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return PasswordReset
     */
    protected function createPasswordReset(array $attributes = []): PasswordReset
    {
        return factory(PasswordReset::class)->create($attributes);
    }
}
