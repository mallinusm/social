<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Models\Post;
use Social\Repositories\DoctrinePostRepository;

/**
 * Class UnpublishPostAction
 * @package Social\Http\Actions\Posts
 */
final class UnpublishPostAction
{
    use AuthorizesRequests;

    /**
     * @var DoctrinePostRepository
     */
    private $doctrinePostRepository;

    /**
     * UnpublishPostAction constructor.
     * @param DoctrinePostRepository $doctrinePostRepository
     */
    public function __construct(DoctrinePostRepository $doctrinePostRepository)
    {
        $this->doctrinePostRepository = $doctrinePostRepository;
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
            throw new AuthorizationException('This action is unauthorized.');
        }
        
        $this->doctrinePostRepository->unpublish($post->getId());
        
        return ['message' => 'The post was deleted.'];
    }
}
