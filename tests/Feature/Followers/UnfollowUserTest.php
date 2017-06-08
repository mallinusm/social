<?php

namespace Tests\Feature\Followers;

use Social\Models\Follower;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UnfollowUserTest
 * @package Tests\Feature\Followers
 */
class UnfollowUserTest extends FeatureTestCase
{
    /** @test */
    function unfollow_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/followers/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function unfollow_unknown_user_following()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/followers/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Follower::class));
    }

    /** @test */
    function unfollow_user_when_not_author()
    {
        $follower = $this->createFollower(['author_id' => $this->createUser()->getId()]);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/followers/{$follower->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseHas('followers', $follower->toArray());
    }

    /** @test */
    function unfollow_user()
    {
        $author = $this->createUser();

        $follower = $this->createFollower(['author_id' => $author->getId()]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/followers/{$follower->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'User unfollowed.']);

        $this->assertDatabaseMissing('followers', $follower->toArray());
    }
}
