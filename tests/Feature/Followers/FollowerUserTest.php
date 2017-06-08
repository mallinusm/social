<?php

namespace Tests\Feature\Followers;

use Social\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class FollowerUserTest
 * @package Tests\Feature\Followers
 */
class FollowerUserTest extends FeatureTestCase
{
    /** @test */
    function follow_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/followers')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function follow_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/followers')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function follow_user()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $attributes = ['author_id' => $author->getAuthIdentifier(), 'user_id' => $userId];

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/followers")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['author_id', 'created_at', 'id', 'updated_at', 'user_id'])
            ->assertJsonFragment($attributes);

        $this->assertDatabaseHas('followers', $attributes);
    }
}
