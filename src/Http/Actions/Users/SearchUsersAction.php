<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\{
    AuthenticationService,
    TransformerService
};
use Social\Transformers\Users\UserTransformer;

/**
 * Class SearchUsersAction
 * @package Social\Http\Actions\Users
 */
final class SearchUsersAction
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
     * @var TransformerService
     */
    private $transformerService;

    /**
     * SearchUsersAction constructor.
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     * @param TransformerService $transformerService
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserRepository $userRepository,
                                TransformerService $transformerService)
    {
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
        $this->transformerService = $transformerService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request): string
    {
        $this->validate($request, [
            'query' => 'required|string|max:255'
        ]);

        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        $users = $this->userRepository->search($request->input('query'), $userId);

        return $this->transformerService
            ->setData($users)
            ->setTransformer((new UserTransformer)->withFollowerState())
            ->toJson();
    }
}
