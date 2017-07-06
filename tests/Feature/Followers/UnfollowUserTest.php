<?php

namespace Tests\Feature\Followers;

use Social\Entities\Follower;
use Social\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UnfollowUserTest
 * @package Tests\Feature\Followers
 */
class UnfollowUserTest extends FeatureTestCase
{
    /** @test */
    function unfollow_user_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/users/1/unfollow')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function unfollow_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/users/1/unfollow')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function unfollow_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/users/123456789/unfollow')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function unfollow_user_when_following()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/users/{$this->createUser()->getId()}/unfollow")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function unfollow_user()
    {
        $user = $this->createUser();
        $userId = $user->getId();

        $author = $this->createUser();

        $follower = $this->createFollower(['author_id' => $author->getId(), 'user_id' => $userId]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/users/{$userId}/unfollow")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'You are no longer following the user.']);

        $this->assertDatabaseMissing('followers', $follower->toArray());
    }
}
