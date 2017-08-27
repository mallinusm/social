<?php

namespace Social\Contracts\Transformers;

use Social\Entities\Reactionable;

/**
 * Interface ReactionableTransformer
 * @package Social\Contracts\Transformers
 */
interface ReactionableTransformer
{
    /**
     * @param Reactionable $reactionable
     * @return array
     */
    public function transform(Reactionable $reactionable): array;

    /**
     * @param Reactionable[] $reactionables
     * @return array
     */
    public function transformMany(array $reactionables): array;
}
