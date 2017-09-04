<?php

namespace Tests\Feature\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class SearchUsersTest
 * @package Tests\Feature\Users
 */
class SearchUsersTest extends FeatureTestCase
{
    /** @test */
    function search_users_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/users/search')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function search_users_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/users/search')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function search_users_without_query()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/users/search')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The query field is required.']);
    }

    /** @test */
    function search_users_with_too_long_query()
    {
        $query = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The query may not be greater than 255 characters.']);
    }

    /** @test */
    function search_users_by_name()
    {
        $user = $this->createUser();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$user->getName()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_by_username()
    {
        $user = $this->createUser();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$user->getUsername()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_by_usernames()
    {
        $query = str_random();

        $user = $this->createUser(['username' => $query]);
        $userTwo = $this->createUser(['username' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ],
                [
                    'avatar' => $userTwo->getAvatar(),
                    'name' => $userTwo->getName(),
                    'username' => $userTwo->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_by_names()
    {
        $query = str_random();

        $user = $this->createUser(['name' => $query]);
        $userTwo = $this->createUser(['name' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ],
                [
                    'avatar' => $userTwo->getAvatar(),
                    'name' => $userTwo->getName(),
                    'username' => $userTwo->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_by_name_and_username()
    {
        $query = str_random();

        $user = $this->createUser(['name' => $query]);
        $userTwo = $this->createUser(['username' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ],
                [
                    'avatar' => $userTwo->getAvatar(),
                    'name' => $userTwo->getName(),
                    'username' => $userTwo->getUsername(),
                    'is_following' => false,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_when_following()
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
            ->getJson("api/v1/users/search?query={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $username,
                    'is_following' => true,
                    'is_followed' => false,
                    'is_mutual' => false
                ]
            ]);
    }

    /** @test */
    function search_users_when_friends()
    {
        $author = $this->createUser();
        $authorId = $author->getId();

        $user = $this->createUser();
        $userId = $user->getId();
        $username = $user->getUsername();

        $this->createFollower([
            'author_id' => $authorId,
            'user_id' => $userId
        ]);

        $this->createFollower([
            'author_id' => $userId,
            'user_id' => $authorId
        ]);

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/users/search?query={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->usersWithFollowStatesJsonStructure())
            ->assertExactJson([
                [
                    'avatar' => $user->getAvatar(),
                    'name' => $user->getName(),
                    'username' => $username,
                    'is_following' => true,
                    'is_followed' => true,
                    'is_mutual' => true
                ]
            ]);
    }
}
