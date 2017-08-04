<?php

namespace Social\Transformers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Social\Contracts\FollowerRepository;
use Social\Entities\User;

/**
 * Class UserTransformer
 * @package Social\Transformers
 */
final class UserTransformer
{
    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * UserTransformer constructor.
     * @param FollowerRepository $followerRepository
     * @param Guard $guard
     */
    public function __construct(FollowerRepository $followerRepository, Guard $guard)
    {
        $this->followerRepository = $followerRepository;
        $this->guard = $guard;
    }

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
     * @param User $user
     * @return array
     */
    public function transformWithFollowerStates(User $user): array
    {
        $userId = $user->getId();

        $authorId = (int) $this->guard->id();

        return array_merge($this->transform($user), [
            'following' => $following = $this->followerRepository->isFollowing($authorId, $userId),
            'followed' => $followed = $this->followerRepository->isFollowing($userId, $authorId),
            'friendship' => $following && $followed
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
