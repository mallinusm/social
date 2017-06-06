<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Models\Follower;

/**
 * Class UnfollowUserAction
 * @package Social\Http\Actions\Followers
 */
class UnfollowUserAction
{
    use AuthorizesRequests;

    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * UnfollowUserAction constructor.
     * @param FollowerRepository $followerRepository
     */
    public function __construct(FollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param Follower $follower
     * @param Request $request
     * @return array
     */
    public function __invoke(Follower $follower, Request $request): array
    {
        $this->authorizeForUser($request->user(), 'delete', $follower);

        $this->followerRepository->unfollow($follower->getId());

        return ['message' => 'User unfollowed.'];
    }
}
