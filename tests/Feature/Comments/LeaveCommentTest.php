<?php

namespace Tests\Feature\Comments;

use Illuminate\Http\Response;
use Social\Models\Post;
use Tests\Feature\FeatureTestCase;

/**
 * Class LeaveCommentTest
 * @package Tests\Feature\Comments
 */
class LeaveCommentTest extends FeatureTestCase
{
    /** @test */
    function leave_comment_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/posts/1/comments')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function leave_comment_for_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/posts/123456789/comments')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function leave_comment_without_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content field is required.']]);
    }

    /** @test */
    function leave_comment_with_too_long_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments", [
                'content' => str_random(256)
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /** @test */
    function leave_comment()
    {
        $content = ['content' => str_random()];

        $author = $this->createUser();

        $postId = $postId = $this->createPost()->getId();

        $data = $content + ['user_id' => $author->getAuthIdentifier(), 'post_id' => $postId];

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/comments", $content)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['content', 'created_at', 'id', 'updated_at', 'post_id', 'user_id'])
            ->assertJsonFragment($data)
            ->assertJsonFragment(['user' => $author->toArray()]);

        $this->assertDatabaseHas('comments', $data);
    }
}
