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
     * @var TransformerService
     */
    private $transformerService;

    /**
     * VisitUserAction constructor.
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
            'username' => 'required|string|max:255'
        ]);

        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        $user = $this->userRepository->visitByUsername($request->input('username'), $userId);

        return $this->transformerService
            ->setData($user)
            ->setTransformer((new UserTransformer)->withFollowerState())
            ->toJson();
    }
}
