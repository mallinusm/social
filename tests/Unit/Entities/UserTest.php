<?php

namespace Tests\Unit\Entities;

use Social\Entities\{
    Follower,
    Post,
    User
};
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit\Entities
 */
class UserTest extends TestCase
{
    /** @test */
    public function id()
    {
        $id = 1;

        $user = new User;

        $user->setId($id);

        $this->assertTrue($user->hasId());
        $this->assertEquals($id, $user->getId());
    }

    /** @test */
    public function email()
    {
        $email = str_random() . '@mail.com';

        $user = new User;

        $user->setEmail($email);

        $this->assertTrue($user->hasEmail());
        $this->assertEquals($email, $user->getEmail());
    }

    /** @test */
    public function name()
    {
        $name = str_random();

        $user = new User;

        $user->setName($name);

        $this->assertTrue($user->hasName());
        $this->assertEquals($name, $user->getName());
    }

    /** @test */
    public function password()
    {
        $password = str_random();

        $user = new User;

        $user->setPassword($password);

        $this->assertTrue($user->hasPassword());
        $this->assertEquals($password, $user->getPassword());
    }

    /** @test */
    public function avatar()
    {
        $avatar = str_random();

        $user = new User;

        $user->setAvatar($avatar);

        $this->assertTrue($user->hasAvatar());
        $this->assertEquals($avatar, $user->getAvatar());
    }

    /** @test */
    public function username()
    {
        $username = str_random();

        $user = new User;

        $user->setUsername($username);

        $this->assertTrue($user->hasUsername());
        $this->assertEquals($username, $user->getUsername());
    }

    /** @test */
    public function created_at()
    {
        $createdAt = time();

        $user = new User;

        $user->setCreatedAt($createdAt);

        $this->assertTrue($user->hasCreatedAt());
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    /** @test */
    public function updated_at()
    {
        $updatedAt = time();

        $user = new User;

        $user->setUpdatedAt($updatedAt);

        $this->assertTrue($user->hasUpdatedAt());
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    /** @test */
    public function posts()
    {
        $posts = [
            new Post
        ];

        $user = new User;

        $user->setPosts($posts);

        $this->assertTrue($user->hasPosts());
        $this->assertEquals($posts, $user->getPosts());
    }

    /** @test */
    public function followers()
    {
        $followers = [
            new Follower
        ];

        $user = new User;

        $user->setFollowers($followers);

        $this->assertTrue($user->hasFollowers());
        $this->assertEquals($followers, $user->getFollowers());
    }

    /** @test */
    public function followings()
    {
        $followings = [
            new Follower
        ];

        $user = new User;

        $user->setFollowings($followings);

        $this->assertTrue($user->hasFollowings());
        $this->assertEquals($followings, $user->getFollowings());
    }
}
