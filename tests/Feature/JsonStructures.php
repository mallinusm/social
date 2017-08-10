<?php

namespace Tests\Feature;

/**
 * Trait JsonStructures
 * @package Tests\Feature
 */
trait JsonStructures
{
    /**
     * @return array
     */
    public function userJsonStructure(): array
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
    public function reactionableJsonStructure(): array
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
    public function oauthJsonStructure(): array
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
    public function reactionablesJsonStructure(): array
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
    public function commentJsonStructure(): array
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
    public function postJsonStructure(): array
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
