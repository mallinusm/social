<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Entities\Comment;

/**
 * Class CommentTransformer
 * @package Social\Transformers
 */
final class CommentTransformer
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * CommentTransformer constructor.
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'content' => $comment->getContent(),
            'created_at' => $comment->getCreatedAt(),
            'updated_at' => $comment->getUpdatedAt(),
            'post_id' => $comment->getPostId(),
            'user' => $this->userTransformer->transform($comment->getUser())
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
