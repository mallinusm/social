<?php

namespace Social\Transformers;

use Social\Entities\Reactionable;

/**
 * Class ReactionableTransformer
 * @package Social\Transformers
 */
final class ReactionableTransformer
{
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
            'reactionable_id' => $reactionable->getReactionableId()
        ];
    }
}
