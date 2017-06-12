<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * @var array
     */
    private $seeders = [
        ReactionsTableSeeder::class,
        UsersTableSeeder::class
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
