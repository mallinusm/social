<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Contracts\PostRepository;
use Social\Repositories\DoctrineFollowerRepository;

/**
 * Class PaginateFeedAction
 * @package Social\Http\Actions\Posts
 */
final class PaginateFeedAction
{
    /**
     * @var FollowerRepository
     */
    private $followerRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PaginateFeedAction constructor.
     * @param FollowerRepository $followerRepository
     * @param PostRepository $postRepository
     */
    public function __construct(FollowerRepository $followerRepository, PostRepository $postRepository)
    {
        $this->followerRepository = $followerRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param Request $request
     * @return Paginator
     */
    public function __invoke(Request $request): Paginator
    {
        $userId = $request->user()->getAuthIdentifier();

        $userIds = $this->followerRepository->getFollowingIds($userId);

        $userIds[] = $userId;

        return $this->postRepository->paginate($userIds);
    }
}
