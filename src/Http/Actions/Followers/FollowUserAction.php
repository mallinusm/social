<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Contracts\UserRepository;

/**
 * Class FollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class FollowUserAction
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
     * FollowUserAction constructor.
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

        $userId = $this->userRepository->findByUsername($request->input('username'))->getId();

        $authorId = $request->user()->getAuthIdentifier();

        if ($this->followerRepository->isFollowing($authorId, $userId)) {
            throw new AuthorizationException('You are already following this user.');
        }

        $this->followerRepository->follow($authorId, $userId);

        return [
            'message' => 'You are now following the user.'
        ];
    }
}
