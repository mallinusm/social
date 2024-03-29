<?php

namespace Tests\Features\Followers;

use Social\Entities\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class UnfollowUserTest
 * @package Tests\Features\Followers
 */
class UnfollowUserTest extends FeatureTestCase
{
    /** @test */
    function unfollow_user_without_json_format()
    {
        $this->assertGuest('api')
            ->delete('api/v1/followers')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function unfollow_user_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->deleteJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function unfollow_user_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username field is required.']);
    }

    /** @test */
    function unfollow_user_with_too_long_username()
    {
        $random = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/followers?username={$random}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username may not be greater than 255 characters.']);
    }

    /** @test */
    function unfollow_unknown_user()
    {
        $random = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/followers?username={$random}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function unfollow_user_when_following()
    {
        $username = $this->createUser()->getUsername();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/followers?username={$username}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'You are not yet following this user.']);
    }

    /** @test */
    function unfollow_user()
    {
        $user = $this->createUser();

        $author = $this->createUser();

        $follower = $this->createFollower(['author_id' => $author->getId(), 'user_id' => $user->getId()]);

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/followers?username={$user->getUsername()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'You are no longer following the user.']);

        $this->assertDatabaseMissing('followers', $follower->toArray());
    }
}
