<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\CommentRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\CommentTransformer;
use Social\Models\Post;

/**
 * Class LeaveCommentAction
 * @package Social\Http\Actions\Comments
 */
final class LeaveCommentAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * LeaveCommentAction constructor.
     * @param AuthenticationService $authenticationService
     * @param CommentRepository $commentRepository
     * @param CommentTransformer $commentTransformer
     */
    public function __construct(AuthenticationService $authenticationService,
                                CommentRepository $commentRepository,
                                CommentTransformer $commentTransformer)
    {
        $this->authenticationService = $authenticationService;
        $this->commentRepository = $commentRepository;
        $this->commentTransformer = $commentTransformer;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return array
     */
    public function __invoke(Post $post, Request $request): array
    {
        $this->validate($request, [
            'content' => 'required|string|max:255'
        ]);

        $user =  $this->authenticationService->getAuthenticatedUser();

        $comment = $this->commentRepository->leave($request->input('content'), $post->getId(), $user->getId());

        $comment->setUser($user);

        return $this->commentTransformer->transform($comment);
    }
}
