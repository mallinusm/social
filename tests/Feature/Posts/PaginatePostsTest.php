<?php

namespace Tests\Feature\Posts;

use Illuminate\Http\Response;
use Social\Models\User;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginatePostsTest
 * @package Tests\Feature\Posts
 */
class PaginatePostsTest extends FeatureTestCase
{
    /** @test */
    function paginate_posts_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/users/1/posts')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function paginate_posts_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/users/1/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_posts_for_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users/123456789/posts')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function paginate_posts()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $post->getId()]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$userId}/posts")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($post->toArray())
            ->assertJsonFragment($comment->toArray())
            ->assertJsonFragment($user->toArray());
    }

    /** @test */
    function paginate_posts_when_upvoting_post()
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
            ->getJson("api/v1/users/{$userId}/posts")
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
    function paginate_posts_when_downvoting_post()
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
            ->getJson("api/v1/users/{$userId}/posts")
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
    function paginate_posts_when_upvoting_comment()
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
            ->getJson("api/v1/users/{$userId}/posts")
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
    function paginate_posts_when_downvoting_comment()
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
            ->getJson("api/v1/users/{$userId}/posts")
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
