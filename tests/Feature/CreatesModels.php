<?php

namespace Tests\Feature;

use Social\Models\{
    Post,
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
}