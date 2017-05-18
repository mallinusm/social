<?php

namespace Tests\Feature\Posts;

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
            ->assertStatus(401)
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
            ->assertStatus(404)
            ->assertJson(['error' => 'No query results for model [Social\\Models\\User].']);
    }

    /**
     * @return void
     */
    public function testCanPaginatePosts(): void
    {
        $user = $this->createUser();

        $userId = $user->getAuthIdentifier();

        $post = $this->createPost([
            'author_id' => $userId,
            'user_id' => $userId
        ]);

        $this->assertDatabaseHas('posts', $post->toArray());

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/users/{$userId}/posts")
            ->assertStatus(200)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment( $post->load('author')->toArray());
    }
}