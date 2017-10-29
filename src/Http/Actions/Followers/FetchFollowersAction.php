<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    FollowerRepository,
    UserRepository
};
use Social\Contracts\Services\TransformerService;
use Social\Transformers\Followers\FollowerTransformer;

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
     * @var TransformerService
     */
    private $transformerService;

    /**
     * FetchFollowersAction constructor.
     * @param UserRepository $userRepository
     * @param FollowerRepository $followerRepository
     * @param TransformerService $transformerService
     */
    public function __construct(UserRepository $userRepository,
                                FollowerRepository $followerRepository,
                                TransformerService $transformerService)
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
        $this->transformerService = $transformerService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request): string
    {
        $this->validate($request, [
            'username' => 'required|string|max:255'
        ]);

        $userId = $this->userRepository->findByUsername($request->input('username'))->getId();

        $followers = $this->followerRepository->getFollowers($userId);

        return $this->transformerService
                ->setData($followers)
                ->setTransformer(FollowerTransformer::class)
                ->toJson();
    }
}
