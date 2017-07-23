<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Repositories\DoctrinePostRepository;
use Social\Transformers\PostTransformer;

/**
 * Class PaginatePostsAction
 * @package Social\Http\Actions\Posts
 */
final class PaginatePostsAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

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
     * @param UserRepository $userRepository
     * @param DoctrinePostRepository $doctrinePostRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(UserRepository $userRepository,
                                DoctrinePostRepository $doctrinePostRepository,
                                PostTransformer $postTransformer)
    {
        $this->userRepository = $userRepository;
        $this->doctrinePostRepository = $doctrinePostRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'username' => 'required|string|max:255'
        ]);

        $user = $this->userRepository->findByUsername($request->input('username'));

        $posts = $this->doctrinePostRepository->paginate([$user->getId()]);

        return $this->postTransformer->transformMany($posts);
    }
}
