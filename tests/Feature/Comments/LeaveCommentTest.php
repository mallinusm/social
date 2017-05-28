<?php

namespace Tests\Feature\Comments;

use Illuminate\Http\Response;
use Social\Models\Post;
use Tests\Feature\FeatureTestCase;

/**
 * Class LeaveCommentTest
 * @package Tests\Feature\Comments
 */
class LeaveCommentTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotLeaveCommentWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/posts/1/comments')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotLeaveCommentForUnknownPost(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/posts/123456789/comments')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(Post::class));
    }

    /**
     * @return void
     */
    public function testCannotLeaveCommentWithoutContent(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['content' => ['The content field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotLeaveCommentWithTooLargeContent(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$this->createPost()->getId()}/comments", [
                'content' => str_random(256)
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /**
     * @return void
     */
    public function testCanLeaveComment(): void
    {
        $content = ['content' => str_random()];

        $author = $this->createUser();

        $postId = $postId = $this->createPost()->getId();

        $data = $content + ['author_id' => $author->getAuthIdentifier(), 'post_id' => $postId];

        $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/posts/{$postId}/comments", $content)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['author_id', 'content', 'created_at', 'id', 'updated_at', 'post_id'])
            ->assertJsonFragment($data)
            ->assertJsonFragment($author->toArray());

        $this->assertDatabaseHas('comments', $data);
    }
}