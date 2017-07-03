<?php

namespace Tests\Feature\Reactions;

use Social\Models\Comment;
use Social\Models\Reaction;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class DownvoteCommentTest
 * @package Tests\Feature\Reactions
 */
class DownvoteCommentTest extends FeatureTestCase
{
    /** @test */
    function downvote_comment_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/comments/1/downvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function downvote_comment_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/comments/1/downvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function downvote_unknown_comment()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/comments/123456789/downvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Comment::class));
    }

    /** @test */
    function downvote_comment_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/comments/{$this->createComment()->getId()}/downvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function downvote_comment_twice()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $commentId = $this->createComment()->getId();

        $reactionable = $this->createReactionable($attributes = [
            'reactionable_id' => $commentId,
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/comments/{$commentId}/downvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);

        /**
         * Delete the original downvote reaction.
         */
        $reactionable->delete();

        /**
         * Make sure the downvote reaction was not inserted twice.
         */
        $this->assertDatabaseMissing('reactionables', $attributes);
    }

    /** @test */
    function downvote_comment()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $commentId = $this->createComment()->getId();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/comments/{$commentId}/downvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'created_at', 'id', 'reactionable_id', 'reactionable_type', 'reaction_id', 'updated_at', 'user',
                'user_id'
            ])
            ->assertJsonFragment($attributes = [
                'reactionable_id' => $commentId,
                'reactionable_type' => 'comments',
                'reaction_id' => $reactionId,
                'user_id' => $user->getId()
            ])
            ->assertJsonFragment(['user' => $user->toArray()]);

        $this->assertDatabaseHas('reactionables', $attributes);
    }
}
