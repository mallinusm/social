<?php

use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Social\Models\{
    Comment, Conversation, Follower, Message, Post, User
};

/** @var Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?? bcrypt('secret'),
        'remember_token' => str_random(10)
    ];
});

$factory->define(Post::class, function (Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'content' => $faker->sentence(),
        'user_id' => $faker->numberBetween(1)
    ];
});

$factory->define(Comment::class, function (Generator $faker) {
    return [
        'content' => $faker->sentence(),
        'post_id' => $faker->numberBetween(1),
        'user_id' => $faker->numberBetween(1)
    ];
});

$factory->define(Conversation::class, function (Generator $faker) {
    return [];
});

$factory->define(Message::class, function (Generator $faker) {
    return [
        'content' => $faker->sentence(),
        'conversation_id' => $faker->numberBetween(1),
        'user_id' => $faker->numberBetween(1)
    ];
});

$factory->define(Follower::class, function (Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'user_id' => $faker->numberBetween(1)
    ];
});
