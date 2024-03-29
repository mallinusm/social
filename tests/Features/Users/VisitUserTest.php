<?php

namespace Tests\Features\Users;

use Social\Entities\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class VisitUserTest
 * @package Tests\Features\Users
 */
class VisitUserTest extends FeatureTestCase
{
    /** @test */
    function visit_user_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/users')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function visit_user_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function visit_user_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username field is required.']);
    }

    /** @test */
    function visit_user_with_too_long_username()
    {
        $random = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users?username={$random}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username may not be greater than 255 characters.']);
    }

    /** @test */
    function visit_unknown_user()
    {
        $random = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users?username={$random}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function visit_user()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $username = $user->getUsername();

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userWithFollowStateJsonStructure())
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'is_following' => false,
                'is_followed' => false,
                'is_mutual' => false,
            ]);
    }

    /** @test */
    function visit_user_when_following()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $username = $user->getUsername();

        $this->createFollower([
            'author_id' => $author->getId(),
            'user_id' => $user->getId()
        ]);

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userWithFollowStateJsonStructure())
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'is_following' => true,
                'is_followed' => false,
                'is_mutual' => false,
            ]);
    }

    /** @test */
    function visit_user_when_friends()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $username = $user->getUsername();

        $this->createFollower([
            'author_id' => $author->getId(),
            'user_id' => $user->getId()
        ]);

        $this->createFollower([
            'author_id' => $user->getId(),
            'user_id' => $author->getId()
        ]);

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userWithFollowStateJsonStructure())
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'is_following' => true,
                'is_followed' => true,
                'is_mutual' => true,
            ]);
    }
}
