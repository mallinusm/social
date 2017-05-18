<?php

namespace Tests\Feature\Posts;

use Social\Models\{
    Post,
    User
};
use Tests\Feature\FeatureTestCase;

/**
 * Class UnpublishPostTest
 * @package Tests\Feature\Posts
 */
class UnpublishPostTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotUnpublishPostWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/1')
            ->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotUnpublishUnknownPost(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/123456789')
            ->assertStatus(404)
            ->assertJson(['error' => 'No query results for model [Social\\Models\\Post].']);
    }

    /**
     * @return void
     */
    public function testCannotUnpublishPostWhenNotAuthor(): void
    {
        $author = $this->createUser();

        $post = $this->createPost([
            'author_id' => $author->getAuthIdentifier()
        ]);

        $postArray = $post->toArray();

        $this->assertDatabaseHas('posts', $postArray);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(403)
            ->assertJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseHas('posts', $postArray);
    }

    /**
     * @return void
     */
    public function testCanUnpublishPostWhenAuthor(): void
    {
        $author = $this->createUser();

        $post = $this->createPost([
            'author_id' => $author->getAuthIdentifier()
        ]);

        $postArray = $post->toArray();

        $this->assertDatabaseHas('posts', $postArray);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(200)
            ->assertJson(['message' => 'The post was deleted.']);

        $this->assertDatabaseMissing('posts', $postArray);
    }
}