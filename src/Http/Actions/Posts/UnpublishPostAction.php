<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Social\Contracts\Repositories\PostRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Models\Post;

/**
 * Class UnpublishPostAction
 * @package Social\Http\Actions\Posts
 */
final class UnpublishPostAction
{
    use AuthorizesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * UnpublishPostAction constructor.
     * @param AuthenticationService $authenticationService
     * @param PostRepository $postRepository
     */
    public function __construct(AuthenticationService $authenticationService, PostRepository $postRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->postRepository = $postRepository;
    }

    /**
     * @param Post $post
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Post $post): array
    {
        if ($this->authenticationService->getAuthenticatedUser()->getId() !== $post->getAuthorId()) {
            throw new AuthorizationException('This post does not belong to you.');
        }
        
        $this->postRepository->unpublish($post->getId());
        
        return ['message' => 'The post was deleted.'];
    }
}
