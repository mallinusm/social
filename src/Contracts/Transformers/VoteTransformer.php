<?php

namespace Social\Contracts\Transformers;

use Social\Entities\Reactionable;

/**
 * Interface VoteTransformer
 * @package Social\Contracts\Transformers
 */
interface VoteTransformer
{
    /**
     * @param Reactionable[] $reactionables
     * @return array
     */
    public function transformMany(array $reactionables): array;
}
