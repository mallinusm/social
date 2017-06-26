<?php

namespace Tests\Feature\Comments;

use Social\Models\Post;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginateCommentsTest
 * @package Tests\Feature\Comments
 */
class PaginateCommentsTest extends FeatureTestCase
{
    /** @test */
    function paginate_comments_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/posts/1/comments')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_comments_for_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/posts/123456789/comments')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function paginate_comments()
    {
        $user = $this->createUser();

        $post = $this->createPost();
        $postId = $post->getId();

        $comment = $this->createComment([
            'post_id' => $postId,
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts/{$postId}/comments")
            ->assertStatus(200)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($comment->toArray())
            ->assertJsonFragment($user->toArray());
    }

    /** @test */
    function paginate_comments_when_downvoting()
    {
        $user = $this->createUser();
        $userId = $user->getId();

        $post = $this->createPost();
        $postId = $post->getId();

        $comment = $this->createComment(['post_id' => $postId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $this->createUser()->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts/{$postId}/comments")
            ->assertStatus(200)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $comment->setAttribute('has_upvoting_count', false)
                    ->setAttribute('has_downvoting_count', true)
                    ->setAttribute('upvoting_count', 0)
                    ->setAttribute('downvoting_count', 2)
                    ->toArray()
            );
    }

    /** @test */
    function paginate_comments_when_upvoting()
    {
        $user = $this->createUser();
        $userId = $user->getId();

        $post = $this->createPost();
        $postId = $post->getId();

        $comment = $this->createComment(['post_id' => $postId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $this->createUser()->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts/{$postId}/comments")
            ->assertStatus(200)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $comment->setAttribute('has_upvoting_count', true)
                    ->setAttribute('has_downvoting_count', false)
                    ->setAttribute('upvoting_count', 2)
                    ->setAttribute('downvoting_count', 0)
                    ->toArray()
            );
    }
}
