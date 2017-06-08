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
            ->assertJsonFragment(['author' => $user->toArray()])
            ->assertJsonFragment(['comments' => [$comment->toArray() + ['user' => $user->toArray()]]]);
    }

    /** @test */
    function paginate_posts_when_upvoting()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'upvote']);

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$userId}/posts")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $post->setAttribute('has_upvoting_count', true)
                    ->setAttribute('has_downvoting_count', false)
                    ->toArray()
            );
    }

    /** @test */
    function paginate_posts_when_downvoting()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $reactionId = $this->createReaction(['name' => 'downvote']);

        $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$userId}/posts")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment(
                $post->setAttribute('has_upvoting_count', false)
                    ->setAttribute('has_downvoting_count', true)
                    ->toArray()
            );
    }
}
