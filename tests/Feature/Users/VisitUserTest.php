<?php

namespace Tests\Feature\Users;

use Social\Entities\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class VisitUserTest
 * @package Tests\Feature\Users
 */
class VisitUserTest extends FeatureTestCase
{
    /** @test */
    function visit_user_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/users')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function visit_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function visit_user_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username field is required.']]);
    }

    /** @test */
    function visit_user_with_too_long_username()
    {
        $random = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users?username={$random}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username may not be greater than 255 characters.']]);
    }

    /** @test */
    function visit_unknown_user()
    {
        $random = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
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
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['name', 'username', 'avatar', 'friendship', 'followed', 'following'])
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'friendship' => false,
                'followed' => false,
                'following' => false,
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
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userJsonStructure() + ['friendship', 'followed', 'following'])
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'friendship' => false,
                'followed' => false,
                'following' => true,
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
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userJsonStructure() + ['friendship', 'followed', 'following'])
            ->assertExactJson([
                'avatar' => $user->getAvatar(),
                'name' => $user->getName(),
                'username' => $username,
                'friendship' => true,
                'followed' => true,
                'following' => true,
            ]);
    }
}
