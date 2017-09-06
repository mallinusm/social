<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Auth\Access\AuthorizationException;
use Social\Contracts\Repositories\CommentRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Models\Comment;

/**
 * Class RemoveCommentAction
 * @package Social\Http\Actions\Comments
 */
final class RemoveCommentAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * DeleteCommentAction constructor.
     * @param AuthenticationService $authenticationService
     * @param CommentRepository $commentRepository
     */
    public function __construct(AuthenticationService $authenticationService, CommentRepository $commentRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Comment $comment
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Comment $comment): array
    {
        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        if ($comment->getUserId() !== $userId) {
            throw new AuthorizationException('The comment does not belong to you.');
        }

        $this->commentRepository->delete($comment->getId());

        return ['message' => 'Comment deleted.'];
    }
}
