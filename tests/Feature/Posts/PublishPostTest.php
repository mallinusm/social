<?php

namespace Tests\Feature\Posts;

use Illuminate\Http\Response;
use Social\Models\User;
use Tests\Feature\FeatureTestCase;

/**
 * Class PublishPostTest
 * @package Tests\Feature\Posts
 */
class PublishPostTest extends FeatureTestCase
{
    /** @test */
    function publish_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function publish_post_for_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/posts')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function publish_post_without_content()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$user->getAuthIdentifier()}/posts")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content field is required.']]);
    }

    /** @test */
    function publish_post_with_too_long_content()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$user->getAuthIdentifier()}/posts", ['content' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /** @test */
    function publish_post()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $data = ['content' => str_random()];

        $database = $data + ['author_id' => $author->getAuthIdentifier(), 'user_id' => $userId];

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/posts", $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['author_id', 'content', 'created_at', 'id', 'updated_at', 'user_id'])
            ->assertJsonFragment(['user' => $user->toArray()])
            ->assertJsonFragment(['author' => $author->toArray()])
            ->assertJsonFragment($database);

        $this->assertDatabaseHas('posts', $database);
    }
}
