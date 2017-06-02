<?php

namespace Social\Repositories;

use Social\Contracts\UserRepository;
use Social\Models\User;

/**
 * Class QueryBuilderUserRepository
 * @package Social\Repositories
 */
class QueryBuilderUserRepository extends QueryBuilderRepository implements UserRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'users';
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @return User
     */
    public function register(string $email, string $name, string $password): User
    {
        return (new User)->fill($this->insert([
            'created_at' => $now = $this->freshTimestamp(),
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'updated_at' => $now
        ]));
    }
}