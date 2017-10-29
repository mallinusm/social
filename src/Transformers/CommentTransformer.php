<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Services\TransformerService;
use Social\Contracts\Transformers\{
    CommentTransformer as CommentTransformerContract,
    VoteTransformer as VoteTransformerContract
};
use Social\Entities\Comment;
use Social\Transformers\Users\UserTransformer;

/**
 * Class CommentTransformer
 * @package Social\Transformers
 */
final class CommentTransformer implements CommentTransformerContract
{
    /**
     * @var TransformerService
     */
    private $transformerService;

    /**
     * @var VoteTransformerContract
     */
    private $voteTransformer;

    /**
     * CommentTransformer constructor.
     * @param TransformerService $transformerService
     * @param VoteTransformerContract $voteTransformer
     */
    public function __construct(TransformerService $transformerService,
                                VoteTransformerContract $voteTransformer)
    {
        $this->transformerService = $transformerService;
        $this->voteTransformer = $voteTransformer;
    }

    /**
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array
    {
        $user = $this->transformerService
            ->setTransformer(UserTransformer::class)
            ->setData($comment->getUser())
            ->toArray();

        return [
            'id' => $comment->getId(),
            'content' => $comment->getContent(),
            'created_at' => $comment->getCreatedAt(),
            'updated_at' => $comment->getUpdatedAt(),
            'post_id' => $comment->getPostId(),
            'user' => $user,
            'reactionables' => $this->voteTransformer->transformMany($comment->getReactionables())
        ];
    }

    /**
     * @param Comment[] $comments
     * @return array
     */
    public function transformMany(array $comments): array
    {
        return (new Collection($comments))->transform(function(Comment $comment): array {
            return $this->transform($comment);
        })->toArray();
    }
}
