<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\PostRepository;
use Social\Models\{
    Post, User
};

/**
 * Class PublishPostAction
 * @package Social\Http\Actions\Posts
 */
class PublishPostAction
{
    use ValidatesRequests;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PublishPostAction constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Post
     */
    public function __invoke(User $user, Request $request): Post
    {
        $this->validate($request, [
            'content' => 'required|string|max:255'
        ]);

        $author = $request->user();

        return $this->postRepository->publish(
            $author->getAuthIdentifier(), $request->input('content'), $user->getAuthIdentifier()
        )->setAttribute('user', $user)->setAttribute('author', $author);
    }
}
