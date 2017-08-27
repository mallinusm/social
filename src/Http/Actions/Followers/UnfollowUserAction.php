<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    FollowerRepository,
    UserRepository
};

/**
 * Class UnfollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class UnfollowUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * UnfollowUserAction constructor.
     * @param UserRepository $userRepository
     * @param FollowerRepository $followerRepository
     */
    public function __construct(UserRepository $userRepository, FollowerRepository $followerRepository)
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'username' => 'required|string|max:255'
        ]);

        $authorId = $request->user()->getAuthIdentifier();

        $userId = $this->userRepository->findByUsername($request->input('username'))->getId();

        if ( ! $this->followerRepository->isFollowing($authorId, $userId)) {
            throw new AuthorizationException('You are not yet following this user.');
        }

        $this->followerRepository->unfollow($authorId, $userId);

        return ['message' => 'You are no longer following the user.'];
    }
}
