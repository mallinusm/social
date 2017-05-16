<?php

namespace Tests\Feature;

use Social\Models\User;

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
}