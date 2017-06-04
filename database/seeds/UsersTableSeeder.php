<?php

use Illuminate\Database\Seeder;
use Social\Models\User;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@social.com'
        ]);

        factory(User::class, 10)->create();
    }
}