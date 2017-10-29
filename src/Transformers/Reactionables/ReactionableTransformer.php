<?php

namespace Social\Transformers\Reactionables;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Social\Entities\Reactionable;
use Social\Transformers\Users\UserTransformer;

/**
 * Class ReactionableTransformer
 * @package Social\Transformers\Reactionables
 */
final class ReactionableTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'author'
    ];

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
            'updated_at' => $reactionable->getUpdatedAt()
        ];
    }

    /**
     * @param Reactionable $reactionable
     * @return Item
     */
    public function includeUser(Reactionable $reactionable): Item
    {
        $user = $reactionable->getUser();

        return $this->item($user, new UserTransformer);
    }
}
