<?php

namespace Social\Http\Actions\Posts;

use Social\Contracts\Repositories\{
    FollowerRepository,
    PostRepository
};
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\PostTransformer;

/**
 * Class PaginateFeedAction
 * @package Social\Http\Actions\Posts
 */
final class PaginateFeedAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PaginateFeedAction constructor.
     * @param AuthenticationService $authenticationService
     * @param FollowerRepository $followerRepository
     * @param PostRepository $postRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(AuthenticationService $authenticationService,
                                FollowerRepository $followerRepository,
                                PostRepository $postRepository,
                                PostTransformer $postTransformer)
    {

        $this->authenticationService = $authenticationService;
        $this->followerRepository = $followerRepository;
        $this->postRepository = $postRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        $userIds = $this->followerRepository->getFollowingIds($userId);

        $userIds[] = $userId;

        $posts = $this->postRepository->paginate($userIds);

        return $this->postTransformer->transformMany($posts);
    }
}
