<?php

namespace Social\Http\Actions\Users;

use Social\Contracts\Services\{
    AuthenticationService,
    TransformerService
};
use Social\Transformers\Users\UserTransformer;

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
     * @var TransformerService
     */
    private $transformerService;

    /**
     * FetchCurrentUserAction constructor.
     * @param AuthenticationService $authenticationService
     * @param TransformerService $transformerService
     */
    public function __construct(AuthenticationService $authenticationService,
                                TransformerService $transformerService)
    {
        $this->authenticationService = $authenticationService;
        $this->transformerService = $transformerService;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        $user = $this->authenticationService->getAuthenticatedUser();

        return $this->transformerService
            ->setData($user)
            ->setTransformer((new UserTransformer)->withEmail())
            ->toJson();
    }
}
