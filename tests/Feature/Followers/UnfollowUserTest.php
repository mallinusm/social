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
    /**
     * @return void
     */
    public function testCannotUnfollowUserWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/followers/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotUnfollowUnknownFollower(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/followers/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(Follower::class));
    }

    /**
     * @return void
     */
    public function testCannotUnfollowUserWhenNotAuthor(): void
    {
        $follower = $this->createFollower(['author_id' => $this->createUser()->getId()]);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/followers/{$follower->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['error' => 'This action is unauthorized.']);
    }

    /**
     * @return void
     */
    public function testCannotUnfollowUser(): void
    {
        $author = $this->createUser();

        $follower = $this->createFollower(['author_id' => $author->getId()]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/followers/{$follower->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'User unfollowed.']);

        $this->assertDatabaseMissing('followers', $follower->toArray());
    }
}