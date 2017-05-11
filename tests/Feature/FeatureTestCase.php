<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Social\Models\User;
use Tests\TestCase;

/**
 * Class FeatureTestCase
 * @package Tests\Feature
 */
abstract class FeatureTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     * @param array $attributes
     * @return User
     */
    public function createUser(array $attributes = []): User
    {
        return factory(User::class)->create($attributes);
    }
}