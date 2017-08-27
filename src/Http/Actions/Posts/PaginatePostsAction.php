<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    PostRepository,
    UserRepository
};
use Social\Contracts\Transformers\PostTransformer;

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
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PaginatePostsAction constructor.
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(UserRepository $userRepository,
                                PostRepository $postRepository,
                                PostTransformer $postTransformer)
    {

        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
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

        $posts = $this->postRepository->paginate([$user->getId()]);

        return $this->postTransformer->transformMany($posts);
    }
}
