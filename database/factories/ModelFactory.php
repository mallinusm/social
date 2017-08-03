<?php

use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Social\Models\{
    Comment,
    Follower,
    Post,
    Reactionable,
    Reaction,
    User
};

/** @var Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => $faker->userName,
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

$factory->define(Follower::class, function (Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'user_id' => $faker->numberBetween(1)
    ];
});

$factory->define(Reaction::class, function (Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Reactionable::class, function (Generator $faker) {
    return [
        'reactionable_id' => $faker->numberBetween(1),
        'reactionable_type' => $faker->numberBetween(1),
        'reaction_id' => $faker->numberBetween(1),
        'user_id' => $faker->numberBetween(1)
    ];
});
