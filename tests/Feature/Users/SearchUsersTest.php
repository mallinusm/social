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
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/users/search')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function search_users_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/users/search')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function search_users_without_query()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users/search')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['query' => ['The query field is required.']]);
    }

    /** @test */
    function search_users_with_too_long_query()
    {
        $query = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['query' => ['The query may not be greater than 255 characters.']]);
    }

    /** @test */
    function search_users_by_name()
    {
        $user = $this->createUser();

        $query = $user->getName();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([array_only($user->toArray(), ['avatar', 'name', 'username'])]);
    }

    /** @test */
    function search_users_by_username()
    {
        $user = $this->createUser();

        $query = $user->getUsername();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([array_only($user->toArray(), ['avatar', 'name', 'username'])]);
    }

    /** @test */
    function search_users_by_usernames()
    {
        $query = str_random();

        $user = $this->createUser(['username' => $query]);
        $userTwo = $this->createUser(['username' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                array_only($user->toArray(), ['avatar', 'name', 'username']),
                array_only($userTwo->toArray(), ['avatar', 'name', 'username']),
            ]);
    }

    /** @test */
    function search_users_by_names()
    {
        $query = str_random();

        $user = $this->createUser(['name' => $query]);
        $userTwo = $this->createUser(['name' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                array_only($user->toArray(), ['avatar', 'name', 'username']),
                array_only($userTwo->toArray(), ['avatar', 'name', 'username']),
            ]);
    }

    /** @test */
    function search_users_by_name_and_username()
    {
        $query = str_random();

        $user = $this->createUser(['name' => $query]);
        $userTwo = $this->createUser(['username' => $query . str_random()]);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/search?query={$query}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                array_only($user->toArray(), ['avatar', 'name', 'username']),
                array_only($userTwo->toArray(), ['avatar', 'name', 'username']),
            ]);
    }
}
