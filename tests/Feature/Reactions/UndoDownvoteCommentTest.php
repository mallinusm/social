<?php

namespace Tests\Feature\Reactions;

use Social\Models\Comment;
use Social\Models\Reaction;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UndoDownvoteCommentTest
 * @package Tests\Feature\Reactions
 */
class UndoDownvoteCommentTest extends FeatureTestCase
{
    /** @test */
    function undo_downvote_comment_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/comments/1/downvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function undo_downvote_comment_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/comments/1/downvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function undo_downvote_unknown_comment()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/comments/123456789/downvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Comment::class));
    }

    /** @test */
    function undo_downvote_comment_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$this->createComment()->getId()}/downvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function undo_downvote_comment_when_not_downvoted()
    {
        $this->createReaction(['name' => 'downvote']);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$this->createComment()->getId()}/downvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function undo_downvote_comment()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $commentId = $this->createComment()->getId();

        $reactionable = $this->createReactionable([
            'reactionable_id' => $commentId,
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$commentId}/downvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Downvote undone.']);

        $this->assertDatabaseMissing('reactionables', $reactionable->toArray());
    }
}
