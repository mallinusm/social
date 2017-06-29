<?php

namespace Tests\Feature\Users;

use Social\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class VisitUserTest
 * @package Tests\Feature\Users
 */
class VisitUserTest extends FeatureTestCase
{
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
            ->assertExactJson($this->entityNotFound(\Social\Entities\User::class));
    }

    /** @test */
    function visit_user()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users?username={$user->getUsername()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($keys = ['name', 'username', 'avatar'])
            ->assertJsonFragment(array_only($user->toArray(), $keys));
    }
}
