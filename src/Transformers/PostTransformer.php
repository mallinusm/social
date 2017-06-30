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
     * PostTransformer constructor.
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
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
            'comments' => $post->hasComments() ? ['TODO transform comments'] : []
        ];
    }
}
