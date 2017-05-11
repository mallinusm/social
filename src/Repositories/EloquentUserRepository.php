<?php

namespace Social\Repositories;

use Social\Contracts\UserRepository;
use Social\Models\User;

/**
 * Class EloquentUserRepository
 * @package Social\Repositories
 */
class EloquentUserRepository implements UserRepository
{
    /**
     * @var User
     */
    private $user;

    /**
     * EloquentUserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function register(string $name, string $email, string $password): User
    {
        return $this->user->newQuery()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }
}