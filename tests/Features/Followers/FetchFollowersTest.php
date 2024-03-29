<?php

namespace Tests\Features\Followers;

use Social\Entities\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class FetchFollowersTest
 * @package Tests\Features\Followers
 */
class FetchFollowersTest extends FeatureTestCase
{
    /** @test */
    function fetch_followers_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/followers')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function fetch_followers_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function fetch_followers_without_username()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username field is required.']);
    }

    /** @test */
    function fetch_followers_with_too_long_username()
    {
        $user = $this->createUser();

        $username = str_random(256);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/followers?username={$username}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username may not be greater than 255 characters.']);
    }

    /** @test */
    function fetch_followers_with_invalid_username()
    {
        $user = $this->createUser();

        $username = str_random();

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/followers?username={$username}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function fetch_followers()
    {
        $user = $this->createUser();

        $follower = $this->createUser();

        $this->createFollower([
            'user_id' => $user->getId(),
            'author_id' => $follower->getId()
        ]);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/followers?username={$user->getUsername()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([['author' => $this->userJsonStructure()]])
            ->assertExactJson([
                [
                    'author' => [
                        'avatar' => $follower->getAvatar(),
                        'username' => $follower->getUsername(),
                        'name' => $follower->getName()
                    ]
                ]
            ]);
    }
}
