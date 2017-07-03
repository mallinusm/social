<?php

namespace Tests\Feature\Reactions;

use Social\Models\{
    Post, Reaction
};
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UpvotePostTest
 * @package Tests\Feature\Reactions
 */
class UpvotePostTest extends FeatureTestCase
{
    /** @test */
    function upvote_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/posts/1/upvote')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function upvote_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/posts/1/upvote')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function upvote_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/posts/123456789/upvote')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function upvote_post_when_unknown_reaction_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/upvote")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Reaction::class));
    }

    /** @test */
    function upvote_post_twice()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $postId = $this->createPost()->getId();

        $reactionable = $this->createReactionable($attributes = [
            'reactionable_id' => $postId,
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/upvote")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);

        /**
         * Delete the original upvote reaction.
         */
        $reactionable->delete();

        /**
         * Make sure the upvote reaction was not inserted twice.
         */
        $this->assertDatabaseMissing('reactionables', $attributes);
    }

    /** @test */
    function upvote_post()
    {
        $user = $this->createUser();

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $postId = $this->createPost()->getId();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/upvote")
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
