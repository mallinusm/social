<?php

namespace Tests\Feature\Posts;

use Tests\Feature\FeatureTestCase;

/**
 * Class PublishPostTest
 * @package Tests\Feature\Posts
 */
class PublishPostTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotPublishPostWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated()
            ->postJson('api/v1/users/1/posts')
            ->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotPublishPostForUnknownUser(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated()
            ->postJson('api/v1/users/123456789/posts')
            ->assertStatus(404)
            ->assertJson(['error' => 'No query results for model [Social\\Models\\User].']);
    }

    /**
     * @return void
     */
    public function testCannotPublishPostWithoutContent(): void
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated()
            ->postJson("api/v1/users/{$user->getAuthIdentifier()}/posts")
            ->assertStatus(422)
            ->assertJsonFragment(['content' => ['The content field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotPublishPostWithTooLargeContent(): void
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated()
            ->postJson("api/v1/users/{$user->getAuthIdentifier()}/posts", ['content' => str_random(256)])
            ->assertStatus(422)
            ->assertJsonFragment(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /**
     * @return void
     */
    public function testCanPublishPost(): void
    {
        $user = $this->createUser();

        $userId = $user->getAuthIdentifier();

        $data = [
            'content' => str_random()
        ];

        $database = $data + ['author_id' => $userId, 'user_id' => $userId];

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated()
            ->postJson("api/v1/users/{$userId}/posts", $data)
            ->assertStatus(200)
            ->assertJsonFragment($database)
            ->assertJsonStructure(['author_id', 'content', 'created_at', 'id', 'updated_at', 'user_id']);

        $this->assertDatabaseHas('posts', $database);
    }
}