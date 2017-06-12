<?php

use Illuminate\Database\Seeder;
use Social\Models\Reaction;

/**
 * Class ReactionsTableSeeder
 * @package database\seeds
 */
class ReactionsTableSeeder extends Seeder
{
    /**
     * @var array
     */
    private $reactions = [
        'downvote', 'upvote'
    ];

    /**
     * @return void
     */
    public function run(): void
    {
        $factory = factory(Reaction::class);

        foreach ($this->reactions as $reaction) {
            $factory->create(['name' => $reaction]);
        }
    }
}
