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

use Social\Models\{
    Comment,
    Post,
    User
};

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?? bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Post::class, function (Faker\Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'content' => $faker->sentence(),
        'user_id' => $faker->numberBetween(1),
    ];
});

$factory->define(Comment::class, function (Faker\Generator $faker) {
    return [
        'author_id' => $faker->numberBetween(1),
        'content' => $faker->sentence(),
        'post_id' => $faker->numberBetween(1),
    ];
});