<?php

namespace Tests\Feature\Reactions;

use Social\Models\{
    Comment, Reaction
};
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UndoUpvoteCommentTest
 * @package Tests\Feature\Reactions
 */
class UndoUpvoteCommentTest extends FeatureTestCase
{
    /** @test */
    function undo_upvote_comment_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/comments/1/upvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function undo_upvote_unknown_comment()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/comments/123456789/upvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Comment::class));
    }

    /** @test */
    function undo_upvote_comment_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$this->createComment()->getId()}/upvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function undo_upvote_comment_when_not_upvoted()
    {
        $this->createReaction(['name' => 'upvote']);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$this->createComment()->getId()}/upvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function undo_upvote_comment()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $commentId = $this->createComment()->getId();

        $reactionable = $this->createReactionable([
            'reactionable_id' => $commentId,
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/comments/{$commentId}/upvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Upvote undone.']);

        $this->assertDatabaseMissing('reactionables', $reactionable->toArray());
    }
}
