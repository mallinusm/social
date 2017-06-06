<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Models\{
    Follower, User
};

/**
 * Class FollowUserAction
 * @package Social\Http\Actions\Followers
 */
class FollowUserAction
{
    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * FollowUserAction constructor.
     * @param FollowerRepository $followerRepository
     */
    public function __construct(FollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Follower
     */
    public function __invoke(User $user, Request $request): Follower
    {
        return $this->followerRepository->follow($request->user()->getAuthIdentifier(), $user->getAuthIdentifier());
    }
}
