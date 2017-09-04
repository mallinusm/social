<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\UserTransformer;

/**
 * Class VisitUserAction
 * @package Social\Http\Actions\Users
 */
final class VisitUserAction
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
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * VisitUserAction constructor.
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserRepository $userRepository,
                                UserTransformer $userTransformer)
    {
        $this->authenticationService = $authenticationService;
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

        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        $user = $this->userRepository->visitByUsername($request->input('username'), $userId);

        return $this->userTransformer->transformWithFollowerState($user);
    }
}
