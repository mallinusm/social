<?php

namespace Social\Http\Actions\Posts;

use Social\Models\User;
use Social\Repositories\DoctrinePostRepository;
use Social\Transformers\PostTransformer;

/**
 * Class PaginatePostsAction
 * @package Social\Http\Actions\Posts
 */
final class PaginatePostsAction
{
    /**
     * @var DoctrinePostRepository
     */
    private $doctrinePostRepository;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PaginatePostsAction constructor.
     * @param DoctrinePostRepository $doctrinePostRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(DoctrinePostRepository $doctrinePostRepository, PostTransformer $postTransformer)
    {
        $this->doctrinePostRepository = $doctrinePostRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @param User $user
     * @return array
     */
    public function __invoke(User $user): array
    {
        return $this->postTransformer->transformMany($this->doctrinePostRepository->paginate([$user->getId()]));
    }
}
