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
     * @var ReactionableTransformer
     */
    private $reactionableTransformer;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var ReactionRepository
     */
    private $reactionRepository;

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
        $this->reactionableTransformer = $reactionableTransformer;
        $this->reactionRepository = $reactionRepository;
        $this->guard = $guard;
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

        $callback = function(Collection $votes, string $name, string $ĥasVoted) use($voteable) {
            $votes->each(function(Reactionable $reactionable) use($voteable, $name): void {
                $voteable->{$name}[] = $this->reactionableTransformer->transform($reactionable);
            })->reject(function(Reactionable $reactionable): bool {
                return $reactionable->getUserId() !== (int) $this->guard->id();
            })->first(function() use($voteable, $ĥasVoted): void {
                $voteable->{$ĥasVoted} = true;
            });
        };

        (new Collection($reactionables))->groupBy(function(Reactionable $reactionable): int {
            return $reactionable->getReactionId();
        })->each(function(Collection $collection, int $key) use($callback): void {
            if ($key === $this->reactionRepository->getUpvoteId()) {
                $callback($collection, 'upvotes', 'has_upvoted');
            } else if ($key === $this->reactionRepository->getDownvoteId()) {
                $callback($collection, 'downvotes', 'has_downvoted');
            }
        });

        return (array) $voteable;
    }
}
