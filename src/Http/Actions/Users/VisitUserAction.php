<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Transformers\UserTransformer;

/**
 * Class VisitUserAction
 * @package Social\Http\Actions\Users
 */
class VisitUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * VisitUserAction constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     * @internal param FollowerRepository $followerRepository
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer)
    {
        $this->userRepository = $userRepository;
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

        return $this->userTransformer->transform(
            $this->userRepository->findByUsername($request->input('username'))
        );
    }
}
