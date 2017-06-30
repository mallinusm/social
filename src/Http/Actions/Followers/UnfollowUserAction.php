<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Models\User;
use Social\Repositories\DoctrineFollowerRepository;

/**
 * Class UnfollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class UnfollowUserAction
{
    use AuthorizesRequests;

    /**
     * @var DoctrineFollowerRepository
     */
    private $followerRepository;

    /**
     * UnfollowUserAction constructor.
     * @param DoctrineFollowerRepository $followerRepository
     */
    public function __construct(DoctrineFollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     */
    public function __invoke(User $user, Request $request): array
    {
        $this->followerRepository->unfollow($request->user()->getAuthIdentifier(), $user->getId());

        return ['message' => 'You are no longer following the user.'];
    }
}
