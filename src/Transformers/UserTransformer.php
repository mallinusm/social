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
            'avatar' => $user->hasAvatar() ? $user->getAvatar(): null,
            'username' => $user->getUsername()
        ];
    }
}
