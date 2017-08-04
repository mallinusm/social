<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Repositories\DoctrineFollowerRepository;
use Social\Transformers\UserTransformer;

/**
 * Class VisitUserAction
 * @package Social\Http\Actions\Users
 */
final class VisitUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var DoctrineFollowerRepository
     */
    private $followerRepository;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * VisitUserAction constructor.
     * @param UserRepository $userRepository
     * @param DoctrineFollowerRepository $followerRepository
     * @param UserTransformer $userTransformer
     * @internal param FollowerRepository $followerRepository
     */
    public function __construct(UserRepository $userRepository,
                                DoctrineFollowerRepository $followerRepository,
                                UserTransformer $userTransformer)
    {
        $this->userRepository = $userRepository;
        $this->followerRepository = $followerRepository;
        $this->userTransformer = $userTransformer;
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

        return $this->userTransformer->transformWithFollowerStates($user);
    }
}
