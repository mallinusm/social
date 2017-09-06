<?php

namespace Tests\Feature\Comments;

use Social\Models\Comment;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class RemoveCommentTest
 * @package Tests\Feature\Comments
 */
class RemoveCommentTest extends FeatureTestCase
{
    /** @test */
    function remove_comment_without_json_format()
    {
        $this->assertGuest('api')
            ->delete('api/v1/comments/1')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function remove_comment_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->deleteJson('api/v1/comments/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function remove_comment_unknown_comment()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson('api/v1/comments/1')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Comment::class));
    }

    /** @test */
    function remove_comment_when_not_owner()
    {
        $comment = $this->createComment();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/comments/{$comment->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'The comment does not belong to you.']);

        $this->assertDatabaseHas('comments', $comment->toArray());
    }

    /** @test */
    function remove_comment_when_owner()
    {
        $user = $this->createUser();

        $comment = $this->createComment([
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->deleteJson("api/v1/comments/{$comment->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Comment deleted.']);

        $this->assertDatabaseMissing('comments', $comment->toArray());
    }
}
