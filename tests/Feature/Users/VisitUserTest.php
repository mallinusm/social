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
            ->getJson('api/v1/users/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function visit_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function visit_user_when_not_following()
    {
        $user = $this->createUser();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$user->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['created_at', 'email', 'id', 'name', 'updated_at', 'following'])
            ->assertJsonFragment($user->toArray())
            ->assertJsonFragment(['following' => false]);
    }

    /** @test */
    function visit_user_when_following()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $userId = $user->getId();

        $this->createFollower(['author_id' => $author->getId(), 'user_id' => $userId]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$userId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['created_at', 'email', 'id', 'name', 'updated_at', 'following'])
            ->assertJsonFragment($user->toArray())
            ->assertJsonFragment(['following' => true]);
    }
}
