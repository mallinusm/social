<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    FollowerRepository,
    UserRepository
};
use Social\Contracts\Services\AuthenticationService;

/**
 * Class FollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class FollowUserAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

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
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     * @param FollowerRepository $followerRepository
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserRepository $userRepository,
                                FollowerRepository $followerRepository)
    {
        $this->authenticationService = $authenticationService;
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

        $authorId = $this->authenticationService->getAuthenticatedUser()->getId();

        if ($this->followerRepository->isFollowing($authorId, $userId)) {
            throw new AuthorizationException('You are already following this user.');
        }

        $this->followerRepository->follow($authorId, $userId);

        return [
            'message' => 'You are now following the user.'
        ];
    }
}
