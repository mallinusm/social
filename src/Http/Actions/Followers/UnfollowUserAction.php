<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Models\User;

/**
 * Class UnfollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class UnfollowUserAction
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
     * @param User $user
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(User $user, Request $request): array
    {
        $authorId = $request->user()->getAuthIdentifier();

        $userId = $user->getId();

        if ( ! $this->followerRepository->isFollowing($authorId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $this->followerRepository->unfollow($authorId, $userId);

        return ['message' => 'You are no longer following the user.'];
    }
}
