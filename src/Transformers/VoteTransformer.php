<?php

namespace Social\Transformers;

use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Social\Contracts\Transformers\{
    ReactionableTransformer as ReactionableTransformerContract,
    VoteTransformer as VoteTransformerContract
};
use Social\Entities\Reactionable;

/**
 * Class VoteTransformer
 * @package Social\Transformers
 */
final class VoteTransformer implements VoteTransformerContract
{
    /**
     * @var array
     */
    private $upvotes = [];

    /**
     * @var array
     */
    private $downvotes = [];

    /**
     * @var array
     */
    private $upvote;

    /**
     * @var array
     */
    private $downvote;

    /**
     * @var bool
     */
    private $hasUpvoted = false;

    /**
     * @var bool
     */
    private $hasDownvoted = false;

    /**
     * @var ReactionableTransformerContract
     */
    private $reactionableTransformer;

    /**
     * @var int
     */
    private $userId;

    /**
     * VoteTransformer constructor.
     * @param ReactionableTransformerContract $reactionableTransformer
     * @param Guard $guard
     * @throws Exception
     */
    public function __construct(ReactionableTransformerContract $reactionableTransformer, Guard $guard)
    {
        $this->reactionableTransformer = $reactionableTransformer;

        $this->userId = (int) $guard->id();
    }

    /**
     * @param Reactionable $reactionable
     */
    private function transformUpvote(Reactionable $reactionable): void
    {
        $this->upvotes[] = $this->reactionableTransformer->transform($reactionable);

        if ($reactionable->getUserId() === $this->userId) {
            $this->hasUpvoted = true;
            $this->upvote = end($this->upvotes);
        }
    }

    /**
     * @param Reactionable $reactionable
     */
    private function transformDownvote(Reactionable $reactionable): void
    {
        $this->downvotes[] = $this->reactionableTransformer->transform($reactionable);

        if ($reactionable->getUserId() === $this->userId) {
            $this->hasDownvoted = true;
            $this->downvote = end($this->upvotes);
        }
    }

    /**
     * @return void
     */
    private function reset(): void
    {
        $this->upvotes = [];
        $this->downvotes = [];
        $this->upvote = null;
        $this->downvote = null;
        $this->hasUpvoted = false;
        $this->hasDownvoted = false;
    }

    /**
     * @return array
     */
    private function toArray(): array
    {
        return [
            'upvotes' => $this->upvotes,
            'downvotes' => $this->downvotes,
            'upvote' => $this->upvote,
            'downvote' => $this->downvote,
            'has_upvoted' => $this->hasUpvoted,
            'has_downvoted' => $this->hasDownvoted
        ];
    }

    /**
     * @return callable
     */
    private function transformVote(): callable
    {
        return function(Reactionable $reactionable): void {
            $reactionId = $reactionable->getReactionId();

            if ($reactionId === 1) {
                $this->transformUpvote($reactionable);
            } else if ($reactionId === 2) {
                $this->transformDownvote($reactionable);
            } else {
                throw new Exception('Unsupported reaction id.');
            }
        };
    }

    /**
     * @param Reactionable[] $reactionables
     * @return array
     */
    public function transformMany(array $reactionables): array
    {
        (new Collection($reactionables))->each($this->transformVote());

        return tap($this->toArray(), function(): void {
            $this->reset();
        });
    }
}
