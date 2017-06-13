<?php

use Illuminate\Database\Seeder;
use Social\Models\Comment;

/**
 * Class CommentsTableSeeder
 */
class CommentsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            factory(Comment::class, 5)->create([
                'post_id' => $i,
                'user_id' => 1
            ]);
        }
    }
}
