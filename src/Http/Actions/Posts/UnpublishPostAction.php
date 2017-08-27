<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\PostRepository;
use Social\Models\Post;

/**
 * Class UnpublishPostAction
 * @package Social\Http\Actions\Posts
 */
final class UnpublishPostAction
{
    use AuthorizesRequests;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * UnpublishPostAction constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Post $post, Request $request): array
    {
        if ($request->user()->getId() !== $post->getAuthorId()) {
            throw new AuthorizationException('This post does not belong to you.');
        }
        
        $this->postRepository->unpublish($post->getId());
        
        return ['message' => 'The post was deleted.'];
    }
}
