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
    /**
     * @return void
     */
    public function testCannotFollowUserWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/followers')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotFollowUnknownUser(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/followers')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(User::class));
    }

    /**
     * @return void
     */
    public function testCanFollowUser(): void
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
