<?php

namespace Tests\Feature\Followers;

use Social\Entities\User;
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
            ->post('api/v1/followers')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function follow_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function follow_user_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/followers')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username field is required.']]);
    }

    /** @test */
    function follow_user_with_too_long_username()
    {
        $random = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/followers?username={$random}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username may not be greater than 255 characters.']]);
    }

    /** @test */
    function follow_unknown_user()
    {
        $random = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/followers?username={$random}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function follow_user_when_already_following()
    {
        $author = $this->createUser();

        $user = $this->createUser();

        $this->createFollower(['author_id' => $author->getId(), 'user_id' => $user->getId()]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/followers?username={$user->getUsername()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function follow_user()
    {
        $author = $this->createUser();

        $user = $this->createUser();

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/followers?username={$user->getUsername()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message'])
            ->assertExactJson([
                'message' => 'You are now following the user.'
            ]);

        $this->assertDatabaseHas('followers', [
            'author_id' => $author->getAuthIdentifier(),
            'user_id' => $user->getId()
        ]);
    }
}
