<?php

namespace Tests\Unit\Entities;

use Social\Entities\{
    Follower,
    User
};
use Tests\TestCase;

/**
 * Class FollowerTest
 * @package Tests\Unit\Entities
 */
class FollowerTest extends TestCase
{
    /** @test */
    public function id()
    {
        $id = 1;

        $follower = new Follower;

        $follower->setId($id);

        $this->assertTrue($follower->hasId());
        $this->assertEquals($id, $follower->getId());
    }

    /** @test */
    public function author_id()
    {
        $id = 1;

        $follower = new Follower;

        $follower->setId($id);

        $this->assertTrue($follower->hasId());
        $this->assertEquals($id, $follower->getId());
    }

    /** @test */
    public function user_id()
    {
        $userId = 1;

        $follower = new Follower;

        $follower->setUserId($userId);

        $this->assertTrue($follower->hasUserId());
        $this->assertEquals($userId, $follower->getUserId());
    }

    /** @test */
    public function created_at()
    {
        $createdAt = time();

        $follower = new Follower;

        $follower->setCreatedAt($createdAt);

        $this->assertTrue($follower->hasCreatedAt());
        $this->assertEquals($createdAt, $follower->getCreatedAt());
    }

    /** @test */
    public function updated_at()
    {
        $updatedAt = time();

        $follower = new Follower;

        $follower->setUpdatedAt($updatedAt);

        $this->assertTrue($follower->hasUpdatedAt());
        $this->assertEquals($updatedAt, $follower->getUpdatedAt());
    }

    /** @test */
    public function author()
    {
        $author = new User;

        $follower = new Follower;

        $follower->setAuthor($author);

        $this->assertTrue($follower->hasAuthor());
        $this->assertEquals($author, $follower->getAuthor());
    }

    /** @test */
    public function user()
    {
        $user = new User;

        $follower = new Follower;

        $follower->setUser($user);

        $this->assertTrue($follower->hasUser());
        $this->assertEquals($user, $follower->getUser());
    }
}
