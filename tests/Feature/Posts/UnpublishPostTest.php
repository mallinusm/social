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
    /** @test */
    function unpublish_post_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/posts/1')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function unpublish_post_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function unpublish_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/posts/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Post::class));
    }

    /** @test */
    function unpublish_post_when_not_author()
    {
        $author = $this->createUser();

        $post = $this->createPost(['author_id' => $author->getAuthIdentifier()]);
        $postArray = $post->toArray();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseHas('posts', $postArray);
    }

    /** @test */
    function unpublish_post_when_author()
    {
        $author = $this->createUser();

        $post = $this->createPost(['author_id' => $author->getAuthIdentifier()]);

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/posts/{$post->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'The post was deleted.']);

        $this->assertDatabaseMissing('posts', $post->toArray());
    }
}
