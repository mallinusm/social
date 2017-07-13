<?php

namespace Social\Transformers;

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
     * @param array $users
     * @return User[]
     */
    public function transformMany(array $users): array
    {
        return array_map(function(User $user): array {
            return $this->transform($user);
        }, $users);
    }
}
