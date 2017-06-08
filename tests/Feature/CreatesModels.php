<?php

namespace Tests\Feature;

use Social\Models\{
    Comment, Conversation, Follower, Message, Post, Reaction, ReactionType, User
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
    public function createUser(array $attributes = []): User
    {
        return factory(User::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Post
     */
    public function createPost(array $attributes = []): Post
    {
        return factory(Post::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Comment
     */
    public function createComment(array $attributes = []): Comment
    {
        return factory(Comment::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Conversation
     */
    public function createConversation(array $attributes = []): Conversation
    {
        return factory(Conversation::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Message
     */
    public function createMessage(array $attributes = []): Message
    {
        return factory(Message::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Follower
     */
    public function createFollower(array $attributes = []): Follower
    {
        return factory(Follower::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return ReactionType
     */
    public function createReactionType(array $attributes = []): ReactionType
    {
        return factory(ReactionType::class)->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Reaction
     */
    public function createReaction(array $attributes = []): Reaction
    {
        return factory(Reaction::class)->create($attributes);
    }
}
