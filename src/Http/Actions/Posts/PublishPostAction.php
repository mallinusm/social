<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\{
    PostRepository,
    UserRepository
};
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\PostTransformer;
use Social\Events\Posts\PostWasPublishedEvent;

/**
 * Class PublishPostAction
 * @package Social\Http\Actions\Posts
 */
final class PublishPostAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * PublishPostAction constructor.
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param PostTransformer $postTransformer
     * @param Dispatcher $dispatcher
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserRepository $userRepository,
                                PostRepository $postRepository,
                                PostTransformer $postTransformer,
                                Dispatcher $dispatcher)
    {
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->postTransformer = $postTransformer;
        $this->dispatcher = $dispatcher;
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

        $author = $this->authenticationService->getAuthenticatedUser();

        $user = $this->userRepository->findByUsername($request->input('username'));

        $post = $this->postRepository
            ->publish($author->getId(), $request->input('content'), $user->getId())
            ->setAuthor($author)
            ->setUser($user);

        $this->dispatcher->dispatch(new PostWasPublishedEvent($post, $this->postTransformer));

        return $this->postTransformer->transform($post);
    }
}
