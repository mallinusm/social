<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Transformers\UserTransformer as UserTransformerContract;
use Social\Entities\User;

/**
 * Class UserTransformer
 * @package Social\Transformers
 */
final class UserTransformer implements UserTransformerContract
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

    /**
     * @param User $user
     * @return array
     */
    public function transformWithFollowerState(User $user): array
    {
        return array_merge($this->transform($user), [
            'is_following' => $isFollowing = $user->hasFollowers(),
            'is_followed' => $isFollowed = $user->hasFollowings(),
            'is_mutual' => $isFollowing && $isFollowed
        ]);
    }

    /**
     * @param array $users
     * @return User[]
     */
    public function transformManyWithFollowerStates(array $users): array
    {
        return (new Collection($users))->transform(function(User $user): array {
            return $this->transformWithFollowerState($user);
        })->toArray();
    }
}
