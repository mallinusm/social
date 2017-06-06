<?php

namespace Social\Http\Actions\Users;

use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Models\User;

/**
 * Class VisitUserAction
 * @package Social\Http\Actions\Users
 */
class VisitUserAction
{
    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * VisitUserAction constructor.
     * @param FollowerRepository $followerRepository
     */
    public function __construct(FollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return User
     */
    public function __invoke(User $user, Request $request): User
    {
        return $user->setAttribute(
            'following', $this->followerRepository->isFollowing($request->user()->getAuthIdentifier(), $user->getId())
        );
    }
}
