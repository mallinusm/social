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
    /**
     * @return void
     */
    public function testCannotPaginatePostsWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/users/1/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotPaginatePostsForUnknownUser(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/users/123456789/posts')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(User::class));
    }

    /**
     * @return void
     */
    public function testCanPaginatePosts(): void
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
            ->assertJsonFragment(['author' => $user->toArray()])
            ->assertJsonFragment(['comments' => [$comment->setAttribute('user', $user->toArray())->toArray()]]);
    }
}