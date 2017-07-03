<?php

namespace Tests\Feature\Reactions;

use Social\Models\Post;
use Social\Models\Reaction;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UndoDownvotePostTest
 * @package Tests\Feature\Reactions
 */
class UndoDownvotePostTest extends FeatureTestCase
{
    /** @test */
    function undo_downvote_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/posts/1/downvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function undo_downvote_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/1/downvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function undo_downvote_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/123456789/downvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function undo_downvote_post_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$this->createPost()->getId()}/downvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function undo_downvote_post_when_not_downvoted()
    {
        $this->createReaction(['name' => 'downvote']);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$this->createPost()->getId()}/downvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function undo_downvote_post()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $postId = $this->createPost()->getId();

        $reactionable = $this->createReactionable([
            'reactionable_id' => $postId,
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$postId}/downvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Downvote undone.']);

        $this->assertDatabaseMissing('reactionables', $reactionable->toArray());
    }
}
