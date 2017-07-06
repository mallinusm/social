<?php

namespace Social\Http\Actions\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionableRepository;

/**
 * Class UndoReactAction
 * @package Social\Http\Actions\Reactionables
 */
final class UndoReactAction
{
    /**
     * @var ReactionableRepository
     */
    private $reactionableRepository;

    /**
     * UndoReactAction constructor.
     * @param ReactionableRepository $reactionableRepository
     */
    public function __construct(ReactionableRepository $reactionableRepository)
    {
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

        if ($reactionable->getUserId() !== $request->user()->getId()) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $this->reactionableRepository->delete($reactionable);

        return ['message' => 'Undid react.'];
    }
}
