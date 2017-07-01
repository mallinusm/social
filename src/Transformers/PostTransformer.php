<?php

namespace Social\Transformers;

use Social\Entities\Post;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * PostTransformer constructor.
     * @param UserTransformer $userTransformer
     * @param CommentTransformer $commentTransformer
     */
    public function __construct(UserTransformer $userTransformer, CommentTransformer $commentTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->commentTransformer = $commentTransformer;
    }

    /**
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
            'author' => $this->userTransformer->transform($post->getAuthor()),
            'user' => $this->userTransformer->transform($post->getUser()),
            'comments' => $post->hasComments() ? $this->commentTransformer->transformMany($post->getComments()) : []
        ];
    }
}
