<?php

namespace Tests\Feature\Posts;

use Illuminate\Http\Response;
use Social\Entities\User;
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
        $this->assertGuest('api')
            ->post('api/v1/posts')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function publish_post_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function publish_post_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The username field is required.']);
    }

    /** @test */
    function publish_post_for_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts', ['username' => str_random(), 'content' => str_random()])
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function publish_post_without_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The content field is required.']);
    }

    /** @test */
    function publish_post_with_too_long_content()
    {
        $user = $this->createUser();

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts', ['content' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The content may not be greater than 255 characters.']);
    }

    /** @test */
    function publish_post()
    {
        $author = $this->createUser();
        $authorId = $author->getAuthIdentifier();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();
        $username = $user->getUsername();

        $content = str_random();

        $this->actingAs($author, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts', [
                'content' => $content,
                'username' => $username
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->postJsonStructure())
            ->assertJsonFragment([
                'content' => $content,
                'user' => [
                    'name' => $user->getName(),
                    'username' => $username,
                    'avatar' => $user->getAvatar()
                ],
                'author' => [
                    'name' => $author->getName(),
                    'username' => $author->getUsername(),
                    'avatar' => $author->getAvatar()
                ],
                'comments' => [],
                'reactionables' => [
                    'upvotes' => [],
                    'downvotes' => [],
                    'upvote' => null,
                    'downvote' => null,
                    'has_upvoted' => false,
                    'has_downvoted' => false
                ]
            ])
            ->assertJsonMissing([
                'author_id' => $authorId,
                'user_id' => $userId
            ]);

        $this->assertDatabaseHas('posts', [
            'content' => $content,
            'author_id' => $authorId,
            'user_id' => $userId
        ]);
    }
}
