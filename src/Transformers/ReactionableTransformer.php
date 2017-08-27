<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Transformers\{
    ReactionableTransformer as ReactionableTransformerContract,
    UserTransformer as UserTransformerContract
};
use Social\Entities\Reactionable;

/**
 * Class ReactionableTransformer
 * @package Social\Transformers
 */
final class ReactionableTransformer implements ReactionableTransformerContract
{
    /**
     * @var UserTransformerContract
     */
    private $userTransformer;

    /**
     * ReactionableTransformer constructor.
     * @param UserTransformerContract $userTransformer
     */
    public function __construct(UserTransformerContract $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param Reactionable $reactionable
     * @return array
     */
    public function transform(Reactionable $reactionable): array
    {
        return [
            'id' => $reactionable->getId(),
            'reaction_id' => $reactionable->getReactionId(),
            'reactionable_type' => $reactionable->getReactionableType(),
            'reactionable_id' => $reactionable->getReactionableId(),
            'created_at' => $reactionable->getCreatedAt(),
            'updated_at' => $reactionable->getUpdatedAt(),
            'user' => $this->userTransformer->transform($reactionable->getUser())
        ];
    }

    /**
     * @param Reactionable[] $reactionables
     * @return array
     */
    public function transformMany(array $reactionables): array
    {
        return (new Collection($reactionables))->transform(function(Reactionable $reactionable): array {
            return $this->transform($reactionable);
        })->toArray();
    }
}
