<?php

namespace Tests\Feature\Posts;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginateFeedTest
 * @package Tests\Feature\Posts
 */
class PaginateFeedTest extends FeatureTestCase
{
    /** @test */
    function paginate_feed_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/feed')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function paginate_feed_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_feed()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();
        $followedId = $this->createUser()->getId();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);
        $followedPost = $this->createPost(['author_id' => $followedId, 'user_id' => $followedId]);

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $post->getId()]);
        $followedComment = $this->createComment(['user_id' => $followedId, 'post_id' => $post->getId()]);

        $this->createFollower(['author_id' => $userId, 'user_id' => $followedId]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($post->toArray())
            ->assertJsonFragment($followedPost->toArray())
            ->assertJsonFragment($comment->toArray())
            ->assertJsonFragment($followedComment->toArray())
            ->assertJsonFragment($user->toArray());
    }

    /** @test */
    function paginate_feed_when_upvoting_post()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'upvote'])->getId();

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $this->createUser()->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $post->setAttribute('has_upvoting_count', true)
                    ->setAttribute('has_downvoting_count', false)
                    ->setAttribute('upvoting_count', 2)
                    ->setAttribute('downvoting_count', 0)
                    ->toArray()
            );
    }

    /** @test */
    function paginate_feed_when_downvoting_post()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'downvote'])->getId();

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $this->createUser()->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $post->setAttribute('has_upvoting_count', false)
                    ->setAttribute('has_downvoting_count', true)
                    ->setAttribute('upvoting_count', 0)
                    ->setAttribute('downvoting_count', 2)
                    ->toArray()
            );
    }

    /** @test */
    function paginate_feed_when_upvoting_comment()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $post->getId()]);

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
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $comment->setAttribute('has_upvoting_count', true)
                    ->setAttribute('has_downvoting_count', false)
                    ->setAttribute('upvoting_count', 2)
                    ->setAttribute('downvoting_count', 0)
                    ->toArray()
            );
    }

    /** @test */
    function paginate_feed_when_downvoting_comment()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $post->getId()]);

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
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $comment->setAttribute('has_upvoting_count', false)
                    ->setAttribute('has_downvoting_count', true)
                    ->setAttribute('upvoting_count', 0)
                    ->setAttribute('downvoting_count', 2)
                    ->toArray()
            );
    }
}
