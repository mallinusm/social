<?php

namespace Tests\Feature\Reactions;

use Social\Models\{
    Post, Reaction
};
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UndoUpvotePostTest
 * @package Tests\Feature\Reactions
 */
class UndoUpvotePostTest extends FeatureTestCase
{
    /** @test */
    function undo_upvote_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/posts/1/upvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function undo_upvote_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/1/upvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function undo_upvote_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/123456789/upvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function undo_upvote_post_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$this->createPost()->getId()}/upvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function undo_upvote_post_when_not_upvoted()
    {
        $this->createReaction(['name' => 'upvote']);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$this->createPost()->getId()}/upvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function undo_upvote_post()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $postId = $this->createPost()->getId();

        $reactionable = $this->createReactionable([
            'reactionable_id' => $postId,
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$postId}/upvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Upvote undone.']);

        $this->assertDatabaseMissing('reactionables', $reactionable->toArray());
    }
}
