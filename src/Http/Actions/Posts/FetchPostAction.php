<?php

namespace Social\Http\Actions\Posts;

use Social\Contracts\Repositories\PostRepository;
use Social\Contracts\Transformers\PostTransformer;

/**
 * Class FetchPostAction
 * @package Social\Http\Actions\Posts
 */
final class FetchPostAction
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * FetchPostAction constructor.
     * @param PostRepository $postRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(PostRepository $postRepository, PostTransformer $postTransformer)
    {
        $this->postRepository = $postRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @param int $postId
     * @return array
     */
    public function __invoke(int $postId): array
    {
        $post = $this->postRepository->fetch($postId);

        return $this->postTransformer->transform($post);
    }
}
