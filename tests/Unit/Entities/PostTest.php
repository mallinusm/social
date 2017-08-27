<?php

namespace Tests\Unit\Entities;

use Social\Entities\{
    Comment,
    Post,
    Reactionable,
    User
};
use Tests\TestCase;

/**
 * Class PostTest
 * @package Tests\Unit\Entities
 */
class PostTest extends TestCase
{
    /** @test */
    public function id()
    {
        $id = 1;

        $post = new Post;

        $post->setId($id);

        $this->assertTrue($post->hasId());
        $this->assertEquals($id, $post->getId());
    }

    /** @test */
    public function author_id()
    {
        $authorId = 1;

        $post = new Post;

        $post->setAuthorId($authorId);

        $this->assertTrue($post->hasAuthorId());
        $this->assertEquals($authorId, $post->getAuthorId());
    }

    /** @test */
    public function content()
    {
        $content = str_random();

        $post = new Post;

        $post->setContent($content);

        $this->assertTrue($post->hasContent());
        $this->assertEquals($content, $post->getContent());
    }

    /** @test */
    public function user_id()
    {
        $userId = 1;

        $post = new Post;

        $post->setUserId($userId);

        $this->assertTrue($post->hasUserId());
        $this->assertEquals($userId, $post->getUserId());
    }

    /** @test */
    public function created_at()
    {
        $createdAt = time();

        $post = new Post;

        $post->setCreatedAt($createdAt);

        $this->assertTrue($post->hasCreatedAt());
        $this->assertEquals($createdAt, $post->getCreatedAt());
    }

    /** @test */
    public function updated_at()
    {
        $updatedAt = time();

        $post = new Post;

        $post->setUpdatedAt($updatedAt);

        $this->assertTrue($post->hasUpdatedAt());
        $this->assertEquals($updatedAt, $post->getUpdatedAt());
    }

    /** @test */
    public function author()
    {
        $author = new User;

        $post = new Post;

        $post->setAuthor($author);

        $this->assertTrue($post->hasAuthor());
        $this->assertEquals($author, $post->getAuthor());
    }

    /** @test */
    public function user()
    {
        $user = new User;

        $post = new Post;

        $post->setUser($user);

        $this->assertTrue($post->hasUser());
        $this->assertEquals($user, $post->getUser());
    }

    /** @test */
    public function comments()
    {
        $comments = [
            new Comment
        ];

        $post = new Post;

        $post->setComments($comments);

        $this->assertTrue($post->hasComments());
        $this->assertEquals($comments, $post->getComments());
    }

    /** @test */
    public function reactionables()
    {
        $reactionables = [
            new Reactionable
        ];

        $post = new Post;

        $post->setReactionables($reactionables);

        $this->assertTrue($post->hasReactionables());
        $this->assertEquals($reactionables, $post->getReactionables());
    }
}
