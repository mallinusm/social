<?php

namespace Social\Http\Actions\Users;

use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\UserTransformer;

/**
 * Class FetchCurrentUserAction
 * @package Social\Http\Actions\Users
 */
final class FetchCurrentUserAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * FetchCurrentUserAction constructor.
     * @param AuthenticationService $authenticationService
     * @param UserTransformer $userTransformer
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserTransformer $userTransformer)
    {
        $this->authenticationService = $authenticationService;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        $user = $this->authenticationService->getAuthenticatedUser();

        return $this->userTransformer->transformWithEmail($user);
    }
}
