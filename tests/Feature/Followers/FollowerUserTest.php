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
    function follow_user_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/users/1/follow')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function follow_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/follow')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function follow_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/follow')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function follow_user_when_already_following()
    {
        $author = $this->createUser();

        $userId = $this->createUser()->getId();

        $this->createFollower(['author_id' => $author->getId(), 'user_id' => $userId]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/follow")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
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
            ->postJson("api/v1/users/{$userId}/follow")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message'])
            ->assertExactJson([
                'message' => 'You are now following the user.'
            ]);

        $this->assertDatabaseHas('followers', $attributes);
    }
}
