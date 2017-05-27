<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Social\Models\{
    Comment, Conversation, Message, Post, User
};

/** @var Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?? bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Post::class, function (Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'content' => $faker->sentence(),
        'user_id' => $faker->numberBetween(1),
    ];
});

$factory->define(Comment::class, function (Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'content' => $faker->sentence(),
        'post_id' => $faker->numberBetween(1),
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