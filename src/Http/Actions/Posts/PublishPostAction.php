<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    PostRepository,
    UserRepository
};
use Social\Contracts\Transformers\PostTransformer;
use Social\Models\User;

/**
 * Class PublishPostAction
 * @package Social\Http\Actions\Posts
 */
class PublishPostAction
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
     * PublishPostAction constructor.
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
            'content' => 'required|string|max:255',
            'username' => 'required|string|max:255'
        ]);

        /** @var User $author */
        $author = $request->user();

        $user = $this->userRepository->findByUsername($request->input('username'));

        $post = $this->postRepository->publish($author->getId(), $request->input('content'), $user->getId());

        $post->setAuthor($author->toUserEntity());

        $post->setUser($user);

        return $this->postTransformer->transform($post);
    }
}
