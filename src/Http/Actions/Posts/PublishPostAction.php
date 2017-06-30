<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\User;
use Social\Repositories\DoctrinePostRepository;
use Social\Transformers\PostTransformer;

/**
 * Class PublishPostAction
 * @package Social\Http\Actions\Posts
 */
class PublishPostAction
{
    use ValidatesRequests;

    /**
     * @var DoctrinePostRepository
     */
    private $postRepository;

    /**
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PublishPostAction constructor.
     * @param DoctrinePostRepository $postRepository
     * @param PostTransformer $postTransformer
     */
    public function __construct(DoctrinePostRepository $postRepository, PostTransformer $postTransformer)
    {
        $this->postRepository = $postRepository;
        $this->postTransformer = $postTransformer;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     */
    public function __invoke(User $user, Request $request): array
    {
        $this->validate($request, [
            'content' => 'required|string|max:255'
        ]);

        /** @var User $author */
        $author = $request->user();

        return $this->postTransformer->transform(
            $this->postRepository->publish(
                $author->getAuthIdentifier(), $request->input('content'), $user->getAuthIdentifier()
            )->setAuthor($author->toUserEntity())->setUser($user->toUserEntity())
        );
    }
}
