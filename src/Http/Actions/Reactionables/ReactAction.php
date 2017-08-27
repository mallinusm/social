<?php

namespace Social\Http\Actions\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\ReactionableRepository;
use Social\Contracts\Transformers\ReactionableTransformer;
use Social\Models\User;

/**
 * Class ReactAction
 * @package Social\Http\Actions\Reactionables
 */
final class ReactAction
{
    use ValidatesRequests;

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
     * @param ReactionableRepository $reactionableRepository
     * @param ReactionableTransformer $reactionableTransformer
     */
    public function __construct(ReactionableRepository $reactionableRepository,
                                ReactionableTransformer $reactionableTransformer)
    {
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

        /**
         * @var User $user
         */
        $user = $request->user();
        $userId = $user->getId();
        $reactionId = $request->input('reaction_id');
        $reactionableId = $request->input('reactionable_id');

        if ($this->reactionableRepository->hasReacted($reactionId, $userId, $reactionableId, $reactionableType)) {
            throw new AuthorizationException('Cannot react twice.');
        }

        $reactionable = $this->reactionableRepository
            ->react($reactionId, $userId, $reactionableId, $reactionableType)
            ->setUser($user->toUserEntity());

        return $this->reactionableTransformer->transform($reactionable);
    }
}
