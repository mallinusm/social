<?php

namespace Tests\Features\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class FetchCurrentUserTest
 * @package Tests\Features\Users
 */
class FetchCurrentUserTest extends FeatureTestCase
{
    /** @test */
    function fetch_current_user_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/user')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function fetch_current_user_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/user')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function fetch_current_user()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/user')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userJsonStructure() + ['email'])
            ->assertExactJson([
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'avatar' => $user->getAvatar(),
                'email' => $user->getEmail()
            ]);
    }
}
