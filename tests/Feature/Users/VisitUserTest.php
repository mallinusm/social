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
    /**
     * @return void
     */
    public function testCannotVisitUserWithoutName(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/users/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonFragment(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotVisitUnknownUser(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonFragment($this->modelNotFoundMessage(User::class));
    }

    /**
     * @return void
     */
    public function testCanVisitUser(): void
    {
        $user = $this->createUser();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$user->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['created_at', 'email', 'id', 'name', 'updated_at', 'following'])
            ->assertJson($user->toArray())
            ->assertJsonFragment(['following' => false]);
    }

    /**
     * @return void
     */
    public function testCanVisitUserWhenFollower(): void
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
            ->assertJson($user->toArray())
            ->assertJsonFragment(['following' => true]);
    }
}
