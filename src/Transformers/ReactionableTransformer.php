<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Services\TransformerService;
use Social\Contracts\Transformers\ReactionableTransformer as ReactionableTransformerContract;
use Social\Entities\Reactionable;
use Social\Transformers\Users\UserTransformer;

/**
 * Class ReactionableTransformer
 * @package Social\Transformers
 */
final class ReactionableTransformer implements ReactionableTransformerContract
{
    /**
     * @var TransformerService
     */
    private $transformerService;

    /**
     * ReactionableTransformer constructor.
     * @param TransformerService $transformerService
     */
    public function __construct(TransformerService $transformerService)
    {
        $this->transformerService = $transformerService;
    }

    /**
     * @param Reactionable $reactionable
     * @return array
     */
    public function transform(Reactionable $reactionable): array
    {
        $user = $this->transformerService
            ->setTransformer(UserTransformer::class)
            ->setData($reactionable->getUser())
            ->toArray();

        return [
            'id' => $reactionable->getId(),
            'reaction_id' => $reactionable->getReactionId(),
            'reactionable_type' => $reactionable->getReactionableType(),
            'reactionable_id' => $reactionable->getReactionableId(),
            'created_at' => $reactionable->getCreatedAt(),
            'updated_at' => $reactionable->getUpdatedAt(),
            'user' => $user
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
