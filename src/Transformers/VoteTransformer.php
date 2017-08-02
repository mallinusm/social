<?php

namespace Social\Transformers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Social\Contracts\ReactionRepository;
use Social\Entities\Reactionable;

/**
 * Class VoteTransformer
 * @package Social\Transformers
 */
final class VoteTransformer
{
    /**
     * @var int
     */
    private $upvotedId;

    /**
     * @var int
     */
    private $downvoteId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var ReactionableTransformer
     */
    private $reactionableTransformer;

    /**
     * VoteTransformer constructor.
     * @param ReactionableTransformer $reactionableTransformer
     * @param ReactionRepository $reactionRepository
     * @param Guard $guard
     */
    public function __construct(ReactionableTransformer $reactionableTransformer,
                                ReactionRepository $reactionRepository,
                                Guard $guard)
    {
        $this->upvotedId = $reactionRepository->getUpvoteId();

        $this->downvoteId = $reactionRepository->getDownvoteId();

        $this->userId = (int) $guard->id();

        $this->reactionableTransformer = $reactionableTransformer;
    }

    /**
     * @param array $reactionables
     * @return array
     */
    public function transformMany(array $reactionables): array
    {
        /**
         * I wish PHP had a struct datatype.
         */
        $voteable = new Class {
            public $upvotes = [], $downvotes = [], $has_upvoted = false, $has_downvoted = false;
        };

        $collection = (new Collection($reactionables))->groupBy(function(Reactionable $reactionable): int {
            return $reactionable->getReactionId();
        });

        if (($upvotes = $collection->get($this->upvotedId)) instanceof Collection) {
            $upvotes->each(function(Reactionable $reactionable) use($voteable): void {
                $voteable->upvotes[] = $this->reactionableTransformer->transform($reactionable);

                if ($reactionable->getUserId() === $this->userId) {
                    $voteable->has_upvoted = true;
                }
            });
        }

        if (($downvotes = $collection->get($this->downvoteId)) instanceof Collection) {
            $upvotes->each(function(Reactionable $reactionable) use($voteable): void {
                $voteable->downvotes[] = $this->reactionableTransformer->transform($reactionable);

                if ($reactionable->getUserId() === $this->userId) {
                    $voteable->has_downvoted = true;
                }
            });
        }

        return (array) $voteable;
    }
}
