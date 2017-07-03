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
    function publish_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/users/1/posts')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

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

        $ids = ['author_id' => $author->getAuthIdentifier(), 'user_id' => $userId];

        $data = ['content' => str_random()];

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/posts", $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['content', 'created_at', 'id', 'updated_at', 'user', 'author'])
            ->assertJsonFragment(['user' => [
                'name' => $user->getAttribute('name'),
                'username' => $user->getUsername(),
                'avatar' => $user->getAvatar()
            ]])
            ->assertJsonFragment(['author' => [
                'name' => $author->getAttribute('name'),
                'username' => $author->getUsername(),
                'avatar' => $author->getAvatar()
            ]])
            ->assertJsonFragment(['comments' => []])
            ->assertJsonFragment($data)
            ->assertJsonMissing($ids);

        $this->assertDatabaseHas('posts', $data + $ids);
    }
}
