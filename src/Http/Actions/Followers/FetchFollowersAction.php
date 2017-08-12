<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Contracts\UserRepository;
use Social\Transformers\FollowerTransformer;

/**
 * Class FetchFollowersAction
 * @package Social\Http\Actions\Followers
 */
final class FetchFollowersAction
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
     * @var FollowerTransformer
     */
    private $followerTransformer;

    /**
     * FetchFollowersAction constructor.
     * @param UserRepository $userRepository
     * @param FollowerRepository $followerRepository
     * @param FollowerTransformer $followerTransformer
     */
    public function __construct(UserRepository $userRepository,
                                FollowerRepository $followerRepository,
                                FollowerTransformer $followerTransformer)
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
        $this->followerTransformer = $followerTransformer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'username' => 'required|string|max:255'
        ]);

        $userId = $this->userRepository->findByUsername($request->input('username'))->getId();

        $followers = $this->followerRepository->getFollowers($userId);

        return $this->followerTransformer->transformMany($followers);
    }
}
