<?php

namespace Social\Http\Actions\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\ReactionableRepository;
use Social\Contracts\Services\AuthenticationService;

/**
 * Class UndoReactAction
 * @package Social\Http\Actions\Reactionables
 */
final class UndoReactAction
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var ReactionableRepository
     */
    private $reactionableRepository;

    /**
     * UndoReactAction constructor.
     * @param AuthenticationService $authenticationService
     * @param ReactionableRepository $reactionableRepository
     */
    public function __construct(AuthenticationService $authenticationService,
                                ReactionableRepository $reactionableRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->reactionableRepository = $reactionableRepository;
    }

    /**
     * @param int $reactionableId
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(int $reactionableId, Request $request): array
    {
        $reactionable = $this->reactionableRepository->find($reactionableId);

        if ($reactionable->getUserId() !== $this->authenticationService->getAuthenticatedUser()->getId()) {
            throw new AuthorizationException('This reactionable does not belong to you.');
        }

        $this->reactionableRepository->delete($reactionable);

        return ['message' => 'Undid react.'];
    }
}
