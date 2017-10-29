<?php

namespace Social\Transformers\Comments;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Social\Entities\Comment;
use Social\Transformers\Users\UserTransformer;

/**
 * Class CommentTransformer
 * @package Social\Transformers\Comments
 */
final class CommentTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'user'
    ];

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
            'post_id' => $comment->getPostId()
        ];
    }

    /**
     * @param Comment $comment
     * @return Item
     */
    public function includeUser(Comment $comment): Item
    {
        $user = $comment->getUser();

        return $this->item($user, new UserTransformer);
    }
}
