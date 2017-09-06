<?php

namespace Tests\Features\Comments;

use Illuminate\Http\Response;
use Social\Models\Post;
use Tests\Features\FeatureTestCase;

/**
 * Class LeaveCommentTest
 * @package Tests\Features\Comments
 */
class LeaveCommentTest extends FeatureTestCase
{
    /** @test */
    function leave_comment_without_json_format()
    {
        $this->assertGuest('api')
            ->post('api/v1/posts/1/comments')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function leave_comment_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/posts/1/comments')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function leave_comment_for_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/posts/123456789/comments')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function leave_comment_without_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The content field is required.']);
    }

    /** @test */
    function leave_comment_with_too_long_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments", [
                'content' => str_random(256)
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['The content may not be greater than 255 characters.']);
    }

    /** @test */
    function leave_comment()
    {
        $content = str_random();

        $user = $this->createUser();
        $userId = $user->getId();

        $postId = $this->createPost()->getId();

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/comments", [
                'content' => $content
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->commentJsonStructure())
            ->assertJsonFragment([
                'content' => $content,
                'post_id' => $postId,
                'user' => [
                    'name' => $user->getName(),
                    'username' => $user->getUsername(),
                    'avatar' => $user->getAvatar()
                ],
                'reactionables' => [
                    'upvotes' => [],
                    'downvotes' => [],
                    'upvote' => null,
                    'downvote' => null,
                    'has_upvoted' => false,
                    'has_downvoted' => false,
                ]
            ])
            ->assertJsonMissing($missing = [
                'user_id' => $userId
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => $content,
            'post_id' => $postId,
            'user_id' => $userId
        ]);
    }
}
