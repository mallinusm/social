<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Entities\User;

/**
 * Class UserTransformer
 * @package Social\Transformers
 */
final class UserTransformer
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'name' => $user->getName(),
            'avatar' => $user->getAvatarLink(),
            'username' => $user->getUsername()
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function transformWithEmail(User $user): array
    {
        return array_merge($this->transform($user), [
            'email' => $user->getEmail()
        ]);
    }

    /**
     * @param array $users
     * @return User[]
     */
    public function transformMany(array $users): array
    {
        return (new Collection($users))->transform(function(User $user): array {
            return $this->transform($user);
        })->toArray();
    }
}
