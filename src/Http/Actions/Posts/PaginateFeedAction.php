<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Http\Request;
use Social\Contracts\FollowerRepository;
use Social\Contracts\PostRepository;
use Social\Transformers\PostTransformer;

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
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PaginateFeedAction constructor.
     * @param FollowerRepository $followerRepository
     * @param PostRepository $postRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(FollowerRepository $followerRepository,
                                PostRepository $postRepository,
                                PostTransformer $postTransformer)
    {

        $this->followerRepository = $followerRepository;
        $this->postRepository = $postRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $userId = $request->user()->getAuthIdentifier();

        $userIds = $this->followerRepository->getFollowingIds($userId);

        $userIds[] = $userId;

        $posts = $this->postRepository->paginate($userIds);

        return $this->postTransformer->transformMany($posts);
    }
}
