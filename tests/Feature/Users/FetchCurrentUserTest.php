<?php

namespace Tests\Feature\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class FetchCurrentUserTest
 * @package Tests\Feature\Users
 */
class FetchCurrentUserTest extends FeatureTestCase
{
    /** @test */
    function fetch_current_user_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/user')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function fetch_current_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/user')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function fetch_current_user()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/user')
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'avatar' => $user->getAvatar()
            ]);
    }
}
