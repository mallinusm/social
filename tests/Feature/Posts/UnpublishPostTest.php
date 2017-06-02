<?php

namespace Tests\Feature\Posts;

use Illuminate\Http\Response;
use Social\Models\Post;
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
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
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
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(Post::class));
    }

    /**
     * @return void
     */
    public function testCannotUnpublishPostWhenNotAuthor(): void
    {
        $author = $this->createUser();

        $post = $this->createPost(['author_id' => $author->getAuthIdentifier()]);
        $postArray = $post->toArray();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseHas('posts', $postArray);
    }

    /**
     * @return void
     */
    public function testCanUnpublishPostWhenAuthor(): void
    {
        $author = $this->createUser();

        $post = $this->createPost(['author_id' => $author->getAuthIdentifier()]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'The post was deleted.']);

        $this->assertDatabaseMissing('posts', $post->toArray());
    }
}