<?php

namespace Social\Transformers\Users;

use League\Fractal\TransformerAbstract;
use Social\Entities\User;

/**
 * Class UserTransformer
 * @package Social\Transformers\Users
 */
final class UserTransformer extends TransformerAbstract
{
    /**
     * @var bool
     */
    private $withEmail;

    /**
     * @var bool
     */
    private $withFollowerState;

    /**
     * UserTransformer constructor.
     * @param bool $withEmail
     * @param bool $withFollowerState
     */
    public function __construct(bool $withEmail = false, bool $withFollowerState = false)
    {
        $this->withEmail = $withEmail;
        $this->withFollowerState = $withFollowerState;
    }

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        $data = [
            'name' => $user->getName(),
            'avatar' => $user->getAvatarLink(),
            'username' => $user->getUsername()
        ];

        if ($this->withEmail) {
            $data['email'] = $user->getEmail();
        }

        if ($this->withFollowerState) {
            /**
             * Followers and followings are eager loaded before this transformer is called.
             * The query was created as such that if there's followers, it means the current
             * authenticated user is following this $user object. If there's followings on
             * this $user object, then it means the current authenticated user is following.
             */
            $data['is_following'] = $isFollowing = $user->hasFollowers();
            $data['is_followed'] = $isFollowed = $user->hasFollowings();
            $data['is_mutual'] = $isFollowing && $isFollowed;
        }

        return $data;
    }

    /**
     * @return UserTransformer
     */
    public function withEmail(): UserTransformer
    {
        $this->withEmail = true;

        return $this;
    }

    /**
     * @return UserTransformer
     */
    public function withFollowerState(): UserTransformer
    {
        $this->withFollowerState = true;

        return $this;
    }
}
