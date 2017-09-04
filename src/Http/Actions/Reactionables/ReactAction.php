<?php

namespace Social\Http\Actions\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\ReactionableRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\ReactionableTransformer;

/**
 * Class ReactAction
 * @package Social\Http\Actions\Reactionables
 */
final class ReactAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var ReactionableRepository
     */
    private $reactionableRepository;

    /**
     * @var ReactionableTransformer
     */
    private $reactionableTransformer;

    /**
     * ReactAction constructor.
     * @param AuthenticationService $authenticationService
     * @param ReactionableRepository $reactionableRepository
     * @param ReactionableTransformer $reactionableTransformer
     */
    public function __construct(AuthenticationService $authenticationService,
                                ReactionableRepository $reactionableRepository,
                                ReactionableTransformer $reactionableTransformer)
    {
        $this->authenticationService = $authenticationService;
        $this->reactionableRepository = $reactionableRepository;
        $this->reactionableTransformer = $reactionableTransformer;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): array
    {
        $reactionableType = $request->input('reactionable_type', 'posts');

        $this->validate($request, [
            'reaction_id' => 'required|integer|exists:reactions,id',
            'reactionable_type' => 'required|string|in:posts,comments',
            'reactionable_id' => "required|integer|exists:{$reactionableType},id"
        ]);

        $user = $this->authenticationService->getAuthenticatedUser();

        $userId = $user->getId();

        $reactionId = $request->input('reaction_id');

        $reactionableId = $request->input('reactionable_id');

        if ($this->reactionableRepository->hasReacted($reactionId, $userId, $reactionableId, $reactionableType)) {
            throw new AuthorizationException('Cannot react twice.');
        }

        $reactionable = $this->reactionableRepository->react($reactionId, $userId, $reactionableId, $reactionableType);

        $reactionable->setUser($user);

        return $this->reactionableTransformer->transform($reactionable);
    }
}
