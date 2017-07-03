<?php

namespace Tests\Feature\Reactions;

use Social\Models\{
    Post, Reaction
};
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class DownvotePostTest
 * @package Tests\Feature\Reactions
 */
class DownvotePostTest extends FeatureTestCase
{
    /** @test */
    function downvote_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/posts/1/downvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function downvote_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/posts/1/downvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function downvote_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/posts/123456789/downvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function downvote_post_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/downvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function downvote_post_twice()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $postId = $this->createPost()->getId();

        $reactionable = $this->createReactionable($attributes = [
            'reactionable_id' => $postId,
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/downvote")
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
    function downvote_post()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $postId = $this->createPost()->getId();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/downvote")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'created_at', 'id', 'reactionable_id', 'reactionable_type', 'reaction_id', 'updated_at', 'user',
                'user_id'
            ])
            ->assertJsonFragment($attributes = [
                'reactionable_id' => $postId,
                'reactionable_type' => 'posts',
                'reaction_id' => $reactionId,
                'user_id' => $user->getId()
            ])
            ->assertJsonFragment(['user' => $user->toArray()]);

        $this->assertDatabaseHas('reactionables', $attributes);
    }
}
