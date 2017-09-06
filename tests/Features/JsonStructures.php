<?php

namespace Tests\Features;

/**
 * Trait JsonStructures
 * @package Tests\Features
 */
trait JsonStructures
{
    /**
     * @return array
     */
    protected function userJsonStructure(): array
    {
        return [
            'name',
            'avatar',
            'username'
        ];
    }

    /**
     * @return array
     */
    protected function userWithFollowStateJsonStructure(): array
    {
        return array_merge($this->userJsonStructure(), [
            'is_following',
            'is_followed',
            'is_mutual'
        ]);
    }

    /**
     * @return array
     */
    protected function usersWithFollowStatesJsonStructure(): array
    {
        return [$this->userWithFollowStateJsonStructure()];
    }

    /**
     * @return array
     */
    protected function reactionableJsonStructure(): array
    {
        return [
            'id',
            'reaction_id',
            'reactionable_id',
            'reactionable_type',
            'created_at',
            'updated_at',
            'user' => $this->userJsonStructure()
        ];
    }

    /**
     * @return array
     */
    protected function oauthJsonStructure(): array
    {
        return [
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ];
    }

    /**
     * @return array
     */
    protected function reactionablesJsonStructure(): array
    {
        return [
            'upvotes',
            'downvotes',
            'upvote',
            'downvote',
            'has_upvoted',
            'has_downvoted',
        ];
    }

    /**
     * @return array
     */
    protected function commentJsonStructure(): array
    {
        return [
            'content',
            'created_at',
            'id',
            'updated_at',
            'post_id',
            'user' => $this->userJsonStructure(),
            'reactionables' => $this->reactionablesJsonStructure()
        ];
    }

    /**
     * @return array
     */
    protected function postJsonStructure(): array
    {
        return [
            'content',
            'created_at',
            'id',
            'updated_at',
            'user' => $this->userJsonStructure(),
            'author' => $this->userJsonStructure(),
            'comments' => [],
            'reactionables' => $this->reactionablesJsonStructure()
        ];
    }
}
