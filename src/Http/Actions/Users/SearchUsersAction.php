<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Transformers\UserTransformer;

/**
 * Class SearchUsersAction
 * @package Social\Http\Actions\Users
 */
final class SearchUsersAction
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
     * SearchUsersAction constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
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
            'query' => 'required|string|max:255'
        ]);

        $users = $this->userRepository->search($request->input('query'), $request->user()->getId());

        return $this->userTransformer->transformManyWithFollowerStates($users);
    }
}
