<?php

use Illuminate\Database\Seeder;
use Social\Models\Post;

/**
 * Class PostsTableSeeder
 * @package database\seeds
 */
class PostsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        factory(Post::class, 10)->create([
            'author_id' => 1,
            'user_id' => 1
        ]);
    }
}
